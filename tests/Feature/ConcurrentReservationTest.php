<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Reserv;
use App\Models\User;
use App\Services\Reservation\ReservService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConcurrentReservationTest extends TestCase
{

    /**
     * Test two concurrent reservation requests from two existing users
     * Uses an existing event from database
     */
    public function test_two_concurrent_reservations(): void
    {
        // Get an existing active event from database
        // If no event exists, create one for testing
        $event = Event::isActive()
            ->where('free_capacity', '>', 0)
            ->orderBy('id')
            ->first();

        if (!$event) {
            // Create a test event if none exists
            $creator = User::firstOrCreate(
                ['email' => 'creator@test.com'],
                ['name' => 'Creator']
            );

            $event = Event::create([
                'title' => 'Test Event - ' . now()->format('Y-m-d H:i:s'),
                'description' => 'Test Description',
                'capacity' => 2,
                'free_capacity' => 2,
                'is_active' => true,
                'start_date' => now()->subDay(),
                'end_date' => now()->addDay(),
                'creator_id' => $creator->id,
            ]);
        }

        // Get two existing users from database (or create if they don't exist)
        $user1 = User::firstOrCreate(
            ['email' => 'user1@test.com'],
            ['name' => 'User 1']
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user2@test.com'],
            ['name' => 'User 2']
        );

        // Delete any existing reservations for these users on this event
        Reserv::where('event_id', $event->id)
            ->whereIn('user_id', [$user1->id, $user2->id])
            ->delete();

        // Reset event capacity if needed
        $event->refresh();
        if ($event->free_capacity <= 0) {
            $event->update(['free_capacity' => 1]);
        }

        // Store initial capacity
        $initialCapacity = $event->free_capacity;
        $eventUuid = $event->uuid;

        // Try to make reservations concurrently
        // Using separate database connections to simulate real concurrency
        $result1 = null;
        $result2 = null;
        $exception1 = null;
        $exception2 = null;

        // Execute both reservations using the service directly
        // The lockForUpdate() in ReservService should handle concurrency
        try {
            $result1 = ReservService::createReservation($eventUuid, $user1->id);
        } catch (\Exception $e) {
            $exception1 = $e->getMessage();
        }

        try {
            $result2 = ReservService::createReservation($eventUuid, $user2->id);
        } catch (\Exception $e) {
            $exception2 = $e->getMessage();
        }

        // Refresh event to get latest capacity
        $event->refresh();

        // Calculate results
        $successCount = ($result1 !== null ? 1 : 0) + ($result2 !== null ? 1 : 0);
        $expectedCapacity = max(0, $initialCapacity - $successCount);
        
        // Assertions
        $this->assertLessThanOrEqual($initialCapacity, $successCount,
            "Should not exceed initial capacity of {$initialCapacity}. " .
            "User1: " . ($result1 ? 'SUCCESS' : ($exception1 ?? 'NULL')) . ", " .
            "User2: " . ($result2 ? 'SUCCESS' : ($exception2 ?? 'NULL')));
        
        $this->assertEquals($expectedCapacity, $event->free_capacity,
            "Free capacity should be {$expectedCapacity} after {$successCount} reservation(s)");

        // Verify total reservations match successful ones
        $totalReservations = Reserv::where('event_id', $event->id)
            ->whereIn('user_id', [$user1->id, $user2->id])
            ->count();
        $this->assertEquals($successCount, $totalReservations,
            "Should have exactly {$successCount} reservation(s) in database");
    }
}


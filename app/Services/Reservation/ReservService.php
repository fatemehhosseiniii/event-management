<?php

namespace App\Services\Reservation;

use App\Models\Event;
use App\Models\Reserv;
use Illuminate\Support\Facades\DB;

class ReservService
{
    /**
     * Create a new reservation for the authenticated user
     * Handles concurrency using database transactions and locks
     *
     * @param string $eventUuid
     * @param int $userId
     * @return Reserv
     * @throws \Exception
     */
    public static function createReservation(string $eventUuid, int $userId): Reserv
    {
        return DB::transaction(function () use ($eventUuid, $userId) {
            $event = self::findAndLockEvent($eventUuid);
            
            EventValidator::validateReservation($event, $userId);
            
            $reserv = self::createReservationRecord($event->id, $userId);
            self::decreaseEventCapacity($event);
            
            return self::loadReservationRelations($reserv);
        });
    }

    /**
     * Find and lock event for update to prevent concurrent modifications
     *
     * @param string $eventUuid
     * @return Event|null
     */
    private static function findAndLockEvent(string $eventUuid): Event|null
    {
        return Event::where('uuid', $eventUuid)
            ->isActive()
            ->lockForUpdate()
            ->first();
    }

    /**
     * Create reservation record in database
     *
     * @param int $eventId
     * @param int $userId
     * @return Reserv
     */
    private static function createReservationRecord(int $eventId, int $userId): Reserv
    {
        return Reserv::create([
            'event_id' => $eventId,
            'user_id' => $userId,
            'is_confirmed' => true,
        ]);
    }

    /**
     * Decrease event free capacity
     *
     * @param Event $event
     * @return void
     */
    private static function decreaseEventCapacity(Event $event): void
    {
        $event->decrement('free_capacity');
    }

    /**
     * Load reservation relationships
     *
     * @param Reserv $reserv
     * @return Reserv
     */
    private static function loadReservationRelations(Reserv $reserv): Reserv
    {
        $reserv->load(['event', 'user']);
        return $reserv;
    }
}


<?php

namespace App\Services\Reservation;

use App\Enums\ReservConfirmed;
use App\Models\Event;
use App\Models\Reserv;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservService
{
    /**
     * Create a new reservation for the authenticated user
     * Handles concurrency using Redis Lock and database transactions
     *
     * @param string $eventUuid
     * @param int $userId
     * @return Reserv
     * @throws \Exception
     */
    public static function createReservation(string $eventUuid, int $userId): Reserv
    {
        $lockKey = "reservation:event:{$eventUuid}";
        $lock = Cache::lock($lockKey, 10);

        try {
            $lock->block(5);

            return DB::transaction(function () use ($eventUuid, $userId) {
                $event = self::findAndLockEvent($eventUuid);
                
                EventValidator::validateReservation($event, $userId);
                
                $reserv = self::createReservationRecord($event->id, $userId);
                self::decreaseEventCapacity($event);
                
                $reserv = self::loadReservationRelations($reserv);
            
                
                self::clearEventCache($eventUuid);
                
                return $reserv;
            });
        } catch (\Exception $e) {
            Log::error('Reservation creation failed', [
                'event_uuid' => $eventUuid,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } finally {
            $lock->release();
        }
    }

    /**
     * Reject reservation
     *
     * @param Reserv $reserv
     * @return Reserv
     * @throws \Exception
     */
    public static function rejectReservation(Reserv $reserv): Reserv
    {
        $lockKey = "reservation:event:{$reserv->event->uuid}";
        $lock = Cache::lock($lockKey, 10);

        try {
            $lock->block(5);

            return DB::transaction(function () use ($reserv) {
                $reserv->update([
                    'is_confirmed' => ReservConfirmed::RejectedConfirmed,
                ]);

                self::increaseEventCapacity($reserv->event);

                $reserv = self::loadReservationRelations($reserv);
                
                self::clearEventCache($reserv->event->uuid);

                return $reserv;
            });
        } finally {
            $lock->release();
        }
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
     * Increase event free capacity
     *
     * @param Event $event
     * @return void
     */
    private static function increaseEventCapacity(Event $event): void
    {
        $event->increment('free_capacity');
    }

    /**
     * Load reservation relationships
     *
     * @param Reserv $reserv
     * @return Reserv
     */
    private static function loadReservationRelations(Reserv $reserv): Reserv
    {
        $reserv->load(['event','user']);
        return $reserv;
    }

    /**
     * Clear event cache after reservation changes
     *
     * @param string $eventUuid
     * @return void
     */
    private static function clearEventCache(string $eventUuid): void
    {
        $page = 1;
        while (Cache::has("events:active:list:page:{$page}")) {
            Cache::forget("events:active:list:page:{$page}");
            $page++;
        }
        Cache::forget("event:{$eventUuid}");
    }
}


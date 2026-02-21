<?php

namespace App\Services\Reservation;

use App\Models\Event;
use App\Models\Reserv;

class EventValidator
{
    /**
     * Validate event exists and is active
     *
     * @param Event|null $event
     * @return void
     * @throws \Exception
     */
    public static function validateEventExists(Event|null $event): void
    {
        if (!$event) {
            throw new \Exception(__('app.reservs.event_not_found'));
        }
    }

    /**
     * Validate user has not already reserved this event
     *
     * @param int $eventId
     * @param int $userId
     * @return void
     * @throws \Exception
     */
    public static function validateUserReservation(int $eventId, int $userId): void
    {
        $existingReserv = Reserv::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if ($existingReserv) {
            throw new \Exception(__('app.reservs.already_reserved'));
        }
    }

    /**
     * Validate event has available capacity
     *
     * @param Event $event
     * @return void
     * @throws \Exception
     */
    public static function validateEventCapacity(Event $event): void
    {
        if ($event->free_capacity <= 0) {
            throw new \Exception(__('app.reservs.event_full'));
        }
    }

    /**
     * Validate all reservation requirements
     *
     * @param Event|null $event
     * @param int $userId
     * @return void
     * @throws \Exception
     */
    public static function validateReservation(Event|null $event, int $userId): void
    {
        self::validateEventExists($event);
        
        // After validateEventExists, $event is guaranteed to be non-null
        /** @var Event $event */
        self::validateUserReservation($event->id, $userId);
        self::validateEventCapacity($event);
    }
}


<?php

namespace App\Enums;

enum ReservConfirmed: int
{
    case Pending = 0;
    case Confirmed = 1;
    case RejectedPending = 2;
    case RejectedConfirmed = 3;

    public function key(): string
    {
        return match ($this) {
            self::Pending     => 'pending',
            self::Confirmed       => 'confirmed',
            self::RejectedPending       => 'rejected_pending',
            self::RejectedConfirmed       => 'rejected_confirmed',
        };
    }
    public function label(): string
    {
        return match ($this) {
            self::Pending     => __('app.reservs.confirmed.pending'),
            self::Confirmed     => __('app.reservs.confirmed.confirmed'),
            self::RejectedPending     => __('app.reservs.confirmed.rejected_pending'),
            self::RejectedConfirmed     => __('app.reservs.confirmed.rejected_confirmed')
        };
    }


    public function toArray(): array
    {
        return [
            'type'       => $this->value,
            'key'        => $this->key(),
            'label'        => $this->label(),
        ];
    }

}

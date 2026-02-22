<?php

namespace App\Models;

use App\Enums\ReservConfirmed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Reserv extends Model
{

    protected $fillable = [
        'uuid',
        'event_id',
        'user_id',
        'is_confirmed',
    ];

    protected $casts = [
        'is_confirmed' => ReservConfirmed::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($reserv) {
            if (empty($reserv->uuid)) {
                $reserv->uuid = (string) Str::uuid();
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

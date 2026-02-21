<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'capacity',
        'free_capacity',
        'start_date',
        'end_date',
        'creator_id',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'free_capacity' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected function ActiveReserv(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['is_active'] == true &&
                (is_null($attributes['start_date']) || $attributes['start_date'] <= now()) &&
                (is_null($attributes['end_date']) || $attributes['end_date'] >= now())
        );
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function scopeIsActive($query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', now()))
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', now()));
    }



    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            $event->uuid = Str::uuid();
            $event->free_capacity = $event->capacity;
            $event->creator_id = auth()->id();
        });
    }
}

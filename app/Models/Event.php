<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'capacity'=>'integer',
        'free_capacity'=>'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }



    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            $event->free_capacity = $event->capacity;
            $event->creator_id = auth()->id();
        });
    }
}

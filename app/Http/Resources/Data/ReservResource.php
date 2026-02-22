<?php

namespace App\Http\Resources\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reserv = $this->resource;
        
        return [
            'code' => $reserv->uuid,
            'event' => $this->when(
                $reserv->relationLoaded('event'),
                fn () => $reserv->event ? [
                    'code' => $reserv->event->uuid,
                    'title' => $reserv->event->title,
                    'active_reserv' => $reserv->event->ActiveReserv
                ]:['is_deleted' => true]
            ),
         
            'confirmed' => $reserv->is_confirmed->label(),
            'confirmed_key' => $reserv->is_confirmed->key(),
            'created_at' => $reserv->created_at,
        ];
    }
}


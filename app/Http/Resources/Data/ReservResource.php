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
        $additional = $this->additional;
        $isPanel = $additional['is_panel'] ?? false;

        return [
            'id' => $this->when($isPanel, $reserv->id),
            'code' => $reserv->uuid,
            'event' => $this->when(
                $reserv->relationLoaded('event'),
                fn() => $reserv->event ? [
                    'id' => $this->when($isPanel, $reserv->event_id),
                    'code' => $reserv->event->uuid,
                    'title' => $reserv->event->title,
                    'active_reserv' => $reserv->event->ActiveReserv
                ] : ['is_deleted' => true]
            ),
            'user' => $this->when(
                $reserv->relationLoaded('user') && $isPanel,
                fn() => $reserv->user ? [
                    'id' => $reserv->user_id,
                    'name' => $reserv->user->name,
                    'email' => $reserv->user->email,
                ] : ['is_deleted' => true]
            ),

            'confirmed' => $reserv->is_confirmed->label(),
            'confirmed_key' => $reserv->is_confirmed->key(),
            'created_at' => $reserv->created_at,
        ];
    }
}

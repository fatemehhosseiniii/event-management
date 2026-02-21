<?php

namespace App\Http\Resources\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $event=$this->resource;
        $additional = $this->additional;
        $isPanel = $additional['is_panel'] ?? false;
        $isShow = $additional['is_show'] ?? false;

        
        return [

            'id'=>$this->when($isPanel, $event->id),

            'title'=>$event->title,
            'description'=>$this->when($isShow, $event->description),

            'capacity'=>$this->when($isPanel, $event->capacity),

            'free_capacity'=>$event->free_capacity,

            'start_date'=>$this->when($isPanel && $isShow, $event->start_date),
            'end_date'=>$this->when($isPanel && $isShow, $event->end_date),
            'is_active'=>$this->when($isPanel && $isShow, $event->is_active),
        
            'creator' => $this->when(
                $event->relationLoaded('creator') && $isPanel,
                fn () => $event->creator?->name ?? $event->creator?->email ?? '-'
            ),
        
            'created_at'=>$event->created_at,
        ];
    }
}

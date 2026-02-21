<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->resource->currentPage(),
            'per_page' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'last_page' => $this->resource->lastPage(),
            'from' => $this->resource->firstItem(),
            'to' => $this->resource->lastItem(),
        ];
    }
}

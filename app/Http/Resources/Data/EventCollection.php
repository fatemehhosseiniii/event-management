<?php

namespace App\Http\Resources\Data;

use App\Http\Resources\BaseResourceCollection;

class EventCollection extends BaseResourceCollection
{
    public $collects = EventResource::class;
    protected string $collectionTitle = 'events';
}

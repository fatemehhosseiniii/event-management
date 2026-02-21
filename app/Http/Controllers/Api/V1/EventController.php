<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Data\EventCollection;
use App\Models\Event;
use App\Services\Response;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $events = Event::query()
            ->isActive()
            ->orderByDesc('created_at')
            ->paginate(config('app.pagination'));

        return Response::success(new EventCollection($events));
    }
}

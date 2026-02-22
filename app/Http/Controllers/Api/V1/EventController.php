<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Data\EventCollection;
use App\Http\Resources\Data\EventResource;
use App\Models\Event;
use App\Repositories\EventRepository;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EventController extends Controller
{


    public function __construct(protected EventRepository $eventRepository)
    {
        //
    }

    /**
     * Get active events
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $page = request()->get('page', 1);

        $events = $this->eventRepository->getActivePaginatedEvents($page);

        return Response::success(new EventCollection($events));
    }

    /**
     * Show event
     *
     * @param string $event
     * @return JsonResponse
     */
    public function show($event): JsonResponse
    {

        //find and check event
        $event = $this->eventRepository->findEventByUuid($event);

        return Response::success((new EventResource($event))->additional(['is_show' => true]));
    }
}

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
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


#[OA\Tag(name: 'Events', description: 'Global Events endpoints')]
class EventController extends Controller
{


    public function __construct(protected EventRepository $eventRepository)
    {
        //
    }

    #[OA\Get(
        path: '/events',
        summary: 'Get list of active events',
        description: 'Retrieve paginated list of active events',
        tags: ['Events'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Page number',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of active events',
                content: new OA\JsonContent(ref: '#/components/schemas/EventListResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $page = request()->get('page', 1);

        $events = $this->eventRepository->getActivePaginatedEvents($page);

        return Response::success(new EventCollection($events));
    }

    #[OA\Get(
        path: '/events/{event}',
        summary: 'Get event details',
        description: 'Retrieve detailed information about a specific event',
        tags: ['Events'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'event',
                in: 'path',
                description: 'Event UUID',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event details',
                content: new OA\JsonContent(ref: '#/components/schemas/EventDetailResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Event not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
        ]
    )]
    public function show($event): JsonResponse
    {

        //find and check event
        $event = $this->eventRepository->findEventByUuid($event);

        return Response::success(['event'=>(new EventResource($event))->additional(['is_show' => true])]);
    }
}

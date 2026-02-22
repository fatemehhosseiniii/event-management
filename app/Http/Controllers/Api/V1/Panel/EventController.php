<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Panel\EventRequest;
use App\Http\Resources\Data\EventCollection;
use App\Http\Resources\Data\EventResource;
use App\Models\Event;
use App\Repositories\EventRepository;
use App\Services\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Panel - Events', description: 'Admin event management endpoints')]
class EventController extends Controller
{

    public function __construct(protected EventRepository $eventRepository)
    {
        //
    }

    #[OA\Get(
        path: '/panel/events',
        summary: 'Get all events (Admin)',
        description: 'Retrieve paginated list of all events for admin panel',
        tags: ['Panel - Events'],
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
                description: 'List of events',
                content: new OA\JsonContent(ref: '#/components/schemas/EventPanelListResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function index()
    {
        //get latest active events
        $events=Event::query()->with('creator')
        // ->filter($request->validated())
        ->orderByDesc('is_active')
        ->orderByDesc('created_at')
        ->paginate(config('app.pagination'));

        //return Result
        return Response::success((new EventCollection($events))->withParameters(['is_panel' => true]));

    }

    #[OA\Get(
        path: '/panel/events/{event}',
        summary: 'Get event details (Admin)',
        description: 'Retrieve detailed information about a specific event',
        tags: ['Panel - Events'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'event',
                in: 'path',
                description: 'Event ID',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event details',
                content: new OA\JsonContent(ref: '#/components/schemas/EventPanelDetailResponse')
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
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function show(Event $event)
    {
        $event->load('creator');
        //return Result
        return Response::success(['event'=>(new EventResource($event))->additional(['is_panel' => true])]);
    }

    #[OA\Post(
        path: '/panel/events',
        summary: 'Create a new event (Admin)',
        description: 'Create a new event',
        tags: ['Panel - Events'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/EventRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/EventPanelDetailResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function store(EventRequest $request)
    {
        //create event
        $event=Event::create($request->validated());

        $event->refresh();
        $event->load('creator');
        $this->eventRepository->clearEventCache();
        //return Result
        return Response::success(['event'=>(new EventResource($event))->additional(['is_panel' => true])]);
    }

    #[OA\Put(
        path: '/panel/events/{event}',
        summary: 'Update an event (Admin)',
        description: 'Update an existing event',
        tags: ['Panel - Events'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'event',
                in: 'path',
                description: 'Event ID',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/EventRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/EventPanelDetailResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Event not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function update(EventRequest $request, Event $event)
    {
        //update event
        $event->update($request->validated());

        $event->refresh();
        $event->load('creator');
        $this->eventRepository->clearEventCache();
        //return Result
        return Response::success(['event'=>(new EventResource($event))->additional(['is_panel' => true])]);
    }

    #[OA\Delete(
        path: '/panel/events/{event}',
        summary: 'Delete an event (Admin)',
        description: 'Delete an event',
        tags: ['Panel - Events'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'event',
                in: 'path',
                description: 'Event ID',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event deleted successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/SuccessResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Event not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function destroy(Event $event)
    {
        //delete event
        $event->delete();
        $this->eventRepository->clearEventCache();
        //return Result
        return Response::success();
    }
}

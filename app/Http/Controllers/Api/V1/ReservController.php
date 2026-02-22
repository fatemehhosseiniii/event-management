<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ReservConfirmed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ReservRequest;
use App\Http\Resources\Data\ReservCollection;
use App\Http\Resources\Data\ReservResource;
use App\Models\Reserv;
use App\Services\Reservation\ReservService;
use App\Services\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\JsonResponse;

#[OA\Tag(name: 'Reservations', description: 'Reservation management endpoints')]
class ReservController extends Controller
{

    #[OA\Get(
        path: '/reservs',
        summary: 'Get user reservations',
        description: 'Retrieve paginated list of current user reservations',
        tags: ['Reservations'],
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
                description: 'List of user reservations',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationListResponse')
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
        $reservs = Reserv::query()
        ->with(['event'])
            ->where('user_id', auth()->id())
            ->orderByDesc('updated_at')
            ->paginate(config('app.pagination'));

        return Response::success(new ReservCollection($reservs));
    }


    #[OA\Post(
        path: '/reservs',
        summary: 'Create a new reservation',
        description: 'Create a new reservation for an event',
        tags: ['Reservations'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ReservRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error or reservation failed',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
        ]
    )]
    public function store(ReservRequest $request): JsonResponse
    {
        try {
            
            $reserv = ReservService::createReservation(
                $request->validated('event_code'),
                auth()->id()
            );

            return Response::success(['reserv'=>(new ReservResource($reserv))]);

        } catch (\Exception $e) {
            return Response::error(
                $e->getMessage(),
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }


    #[OA\Put(
        path: '/reservs/{reserv}',
        summary: 'Cancel a reservation',
        description: 'Cancel a confirmed reservation (only if created within last 6 hours)',
        tags: ['Reservations'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'reserv',
                in: 'path',
                description: 'Reservation UUID',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation cancelled successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Reservation not found or cannot be cancelled',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
        ]
    )]
    public function update($reserv)
    {
        //find
        $reserv = Reserv::where('user_id', auth()->id())
        ->where('is_confirmed', ReservConfirmed::Confirmed)
        ->where('created_at', '>=', now()->subHours(6))
        ->where('uuid', $reserv)->first();
        if(!$reserv) {
            return Response::error(__('app.reservs.not_found'), HttpResponse::HTTP_NOT_FOUND);
        }

        //save confirmed cancelled
        $reserv->update([
            'is_confirmed' => ReservConfirmed::RejectedPending,
        ]);
        $reserv->load(['event']);

        return Response::success(['reserv'=>(new ReservResource($reserv))]);
    }

}

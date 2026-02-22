<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Enums\ReservConfirmed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Panel\ReservRequest;
use App\Http\Resources\Data\ReservCollection;
use App\Http\Resources\Data\ReservResource;
use App\Models\Reserv;
use App\Services\Reservation\ReservService;
use App\Services\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[OA\Tag(name: 'Panel - Reservations', description: 'Admin reservation management endpoints')]
class ReservController extends Controller
{
    #[OA\Get(
        path: '/panel/reservs',
        summary: 'Get all reservations (Admin)',
        description: 'Retrieve paginated list of all reservations for admin panel',
        tags: ['Panel - Reservations'],
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
                description: 'List of reservations',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationPanelListResponse')
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
        $reservs=Reserv::query()->with('event','user')
        // ->filter($request->validated())
        ->orderByDesc('created_at')
        ->paginate(config('app.pagination'));

        //return Result
        return Response::success((new ReservCollection($reservs))->withParameters(['is_panel' => true]));
    }

    #[OA\Put(
        path: '/panel/reservs/{reserv}',
        summary: 'Approve or reject reservation (Admin)',
        description: 'Approve or reject a pending reservation',
        tags: ['Panel - Reservations'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'reserv',
                in: 'path',
                description: 'Reservation ID',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ReservUpdateRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationPanelResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Reservation not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid confirmation type or validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function update(ReservRequest $request, Reserv $reserv)
    {
        //check valid Confirm Type Reserv
        if($reserv->is_confirmed != ReservConfirmed::RejectedPending) {
            return Response::error(__('app.reservs.invalid_confirm_type'), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $reserv = ReservService::rejectReservation($reserv);

        //return Result
        return Response::success((new ReservResource($reserv))->additional(['is_panel' => true]));
    }

    #[OA\Delete(
        path: '/panel/reservs/{reserv}',
        summary: 'Delete a reservation (Admin)',
        description: 'Delete a reservation',
        tags: ['Panel - Reservations'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'reserv',
                in: 'path',
                description: 'Reservation ID',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation deleted successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/SuccessResponse')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'Reservation not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Admin access required',
                content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
            )
        ]
    )]
    public function destroy(Reserv $reserv)
    {
        ReservService::removeReservation($reserv);
        return Response::success();
    }
    
}

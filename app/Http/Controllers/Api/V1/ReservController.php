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
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\JsonResponse;

class ReservController extends Controller
{

    public function index(): JsonResponse
    {
        $reservs = Reserv::query()
        ->with(['event'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(config('app.pagination'));

        return Response::success(new ReservCollection($reservs));
    }


    public function store(ReservRequest $request): JsonResponse
    {
        try {
            
            $reserv = ReservService::createReservation(
                $request->validated('event_code'),
                auth()->id()
            );

            return Response::success(new ReservResource($reserv));

        } catch (\Exception $e) {
            return Response::error(
                $e->getMessage(),
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }


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

        return Response::success(new ReservResource($reserv));
    }

}

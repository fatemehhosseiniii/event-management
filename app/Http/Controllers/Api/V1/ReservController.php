<?php

namespace App\Http\Controllers\Api\V1;

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


}

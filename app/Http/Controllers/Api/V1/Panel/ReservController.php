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
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ReservController extends Controller
{
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
    
}

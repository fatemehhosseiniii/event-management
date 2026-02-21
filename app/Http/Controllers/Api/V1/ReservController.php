<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ReservRequest;
use App\Http\Resources\Data\ReservCollection;
use App\Http\Resources\Data\ReservResource;
use App\Models\Event;
use App\Models\Reserv;
use App\Services\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\JsonResponse;

class ReservController extends Controller
{
    /**
     * Display a listing of the user's reservations.
     */
    public function index(): JsonResponse
    {
        $reservs = Reserv::query()
        ->with(['event'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(config('app.pagination'));

        return Response::success(new ReservCollection($reservs));
    }

    /**
     * Store a newly created reservation.
     */
    public function store(ReservRequest $request)
    {
        $event = Event::where('uuid', $request->validated('event_code'))->isActive()->first();

        //check user has already reserved this event
        $reserv = Reserv::where('event_id', $event->id)->where('user_id', auth()->id())->first();
        if ($reserv)
            return Response::error(__('app.reservs.already_reserved'), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        //check free capacity
        if ($event->free_capacity <= 0)
            return Response::error(__('app.reservs.event_full'), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);



        // Create reservation
        $reserv = Reserv::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);


        $reserv->load(['event', 'user']);

        return Response::success(new ReservResource($reserv));
    }

    public function confirm($reserv)
    {
        //find reserv
        $reserv = Reserv::where('uuid', $reserv)->where('user_id', auth()->id())->first();
        if (!$reserv)
            return Response::error(__('app.reservs.not_found'), HttpResponse::HTTP_NOT_FOUND);

        $reserv->update(['is_confirmed' => true]);

        // Decrease free capacity
        $reserv->event->decrement('free_capacity');
        $reserv->load(['event', 'user']);

        return Response::success(new ReservResource($reserv));
    }


}

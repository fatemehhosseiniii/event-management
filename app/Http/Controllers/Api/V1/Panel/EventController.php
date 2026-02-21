<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Panel\EventRequest;
use App\Http\Resources\Data\EventCollection;
use App\Http\Resources\Data\EventResource;
use App\Models\Event;
use App\Services\Response;

class EventController extends Controller
{

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

    public function show(Event $event)
    {
        $event->load('creator');
        //return Result
        return Response::success((new EventResource($event))->additional(['is_panel' => true,'is_show' => true]));
    }

    public function store(EventRequest $request)
    {
        //create event
        $event=Event::create($request->validated());

        $event->load('creator');
        //return Result
        return Response::success((new EventResource($event))->additional(['is_panel' => true]));
    }

    public function update(EventRequest $request, Event $event)
    {
        //update event
        $event->update($request->validated());

        $event->load('creator');
        //return Result
        return Response::success((new EventResource($event))->additional(['is_panel' => true,'is_show' => true]));
    }

    public function destroy(Event $event)
    {
        //delete event
        $event->delete();
        //return Result
        return Response::success();
    }
}

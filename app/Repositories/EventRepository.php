<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventRepository
{
    /**
     * Get active paginated events
     *
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getActivePaginatedEvents(int $page): LengthAwarePaginator
    {
        $cacheKey = 'events:active:list';
        return Cache::remember(
            "{$cacheKey}:page:{$page}",
            300, // 5 minutes cache
            function () use ($page) {
                return Event::query()
                    ->isActive()
                    ->orderByDesc('created_at')
                    ->orderBy('free_capacity')
                    ->paginate(config('app.pagination'), ['*'], 'page', $page);
            }
        );
    }

    /**
     * Find event by uuid
     *
     * @param string $uuid
     * @return Event
     * @throws \Exception
     */
    public function findEventByUuid(string $uuid): Event
    {
        $event= Event::where('uuid', $uuid)->isActive()->first();
        if(!$event) {
            throw new NotFoundHttpException(__('app.events.not_found'));
        }
        return $event;
    }

    
     /**
     * Clear event cache after reservation changes
     *
     * @param string $eventUuid
     * @return void
     */
    public function clearEventCache(): void
    {
        $page = 1;
        while (Cache::has("events:active:list:page:{$page}")) {
            Cache::forget("events:active:list:page:{$page}");
            $page++;
        }
    }

}
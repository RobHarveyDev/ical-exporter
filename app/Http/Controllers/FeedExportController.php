<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as IcalEvent;

class FeedExportController extends Controller
{
    public function __invoke(Request $request, Feed $feed)
    {
        if ($feed->reject_head_requests && $request->isMethod('HEAD')) {
            abort(405);
        }

        $feed->update([
            'last_accessed_at' => now(),
        ]);

        $calendar = Calendar::create($feed->name);

        $events = $feed->events()
            ->whereDate('start_at', '>=', today())
            ->get()
            ->map(fn (Event $event) => IcalEvent::create()
                ->name($event->title)
                ->startsAt($event->start_at)
                ->endsAt($event->end_at)
            );

        $calendar->event($events->toArray());

        return response($calendar->get(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.Str::snake($feed->name).'.ics"',
        ]);
    }
}

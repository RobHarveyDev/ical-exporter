<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventsController extends Controller
{
    public function store(Request $request, Feed $feed)
    {
        if ($request->user()->cannot('update', $feed)) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $feed->events()->create([
            'uuid' => Str::uuid(),
            'title' => $request->string('title'),
            'start_at' => $request->date('start_date')->setTime(12, 0),
            'end_at' => $request->date('end_date')->setTime(11, 59),
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, Feed $feed, Event $event)
    {
        if ($request->user()->cannot('update', $feed)) {
            abort(403);
        }

        $event->delete();

        return redirect()->back();
    }
}

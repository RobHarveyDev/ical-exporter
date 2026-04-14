<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeedsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:64'],
            'reject_head_requests' => ['sometimes', 'boolean'],
        ]);

        $request->user()->feeds()->create([
            'uuid' => Str::uuid(),
            'name' => $request->string('name'),
            'reject_head_requests' => $request->boolean('reject_head_requests'),
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Feed $feed)
    {
        if ($request->user()->cannot('update', $feed)) {
            abort(403);
        }

        $request->validate([
            'reject_head_requests' => ['sometimes', 'boolean'],
        ]);

        $feed->update([
            'reject_head_requests' => $request->boolean('reject_head_requests'),
        ]);

        return redirect()->back();
    }

    public function show(Request $request, Feed $feed)
    {
        if ($request->user()->cannot('view', $feed)) {
            abort(403);
        }

        $feed->load('events');

        return view('dashboard', [
            'feeds' => $request->user()->feeds()->latest()->get(),
            'currentFeed' => $feed,
        ]);
    }
}

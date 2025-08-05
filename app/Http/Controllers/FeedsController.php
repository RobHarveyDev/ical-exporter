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
            'name'=> ['required', 'string', 'max:64'],
        ]);

        $request->user()->feeds()->create([
            'uuid' => Str::uuid(),
            'name' => $request->string('name'),
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
            'feeds' => Feed::all(),
            'currentFeed' => $feed
        ]);
    }
}

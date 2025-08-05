<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard', [
            'feeds' => $request->user()->feeds()->latest()->get(),
        ]);
    }
}

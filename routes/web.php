<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FeedExportController;
use App\Http\Controllers\FeedsController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::post('/feeds', [FeedsController::class, 'store'])->name('feeds.store');
    Route::get('/feeds/{feed:uuid}', [FeedsController::class, 'show'])->name('feeds.show');

    Route::post('/feeds/{feed:uuid}/event', [EventsController::class, 'store'])->name('events.store');
    Route::delete('/feeds/{feed:uuid}/event/{event:uuid}', [EventsController::class, 'destroy'])->name('events.destroy');
});

Route::get('/export/feeds/{feed:uuid}', FeedExportController::class)->name('feeds.export');

require __DIR__.'/auth.php';

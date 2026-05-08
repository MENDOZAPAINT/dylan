<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ChatController;


Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

// Route::get('/chat', [ChatController::class, 'index']);
// Route::post('/send-message', [ChatController::class, 'sendMessage']);
Route::get('/chat', [ChatController::class, 'index']);
Route::post('/enviar-mensaje', [ChatController::class, 'sendMessage']);

require __DIR__.'/settings.php';
require __DIR__.'/category.php';
require __DIR__.'/dish.php';
require __DIR__.'/table.php';
require __DIR__.'/order.php';

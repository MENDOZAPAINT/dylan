<?php

use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('tables', [TableController::class, 'index'])->name('tables.index');
    Route::post('tables', [TableController::class, 'store'])->name('tables.store');
    Route::put('tables/{table}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
});

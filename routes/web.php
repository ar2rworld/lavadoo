<?php

use App\Http\Controllers\CounterController;
use Illuminate\Support\Facades\Route;
use App\Models\Counter;

Route::get('/', function () {
    $counters = Counter::all();
    return view('welcome', compact('counters'));
});


Route::get('/a', function () {
    return view('dashboard', ['name' => "abc"]);
});

// Use plural here
Route::resource('counters', CounterController::class);

Route::put('/counters/{id}', [CounterController::class, 'update'])->name('counters.update');
Route::post('/counters/{counter}/increment', [CounterController::class, 'increment'])->name('counters.increment');
Route::post('/counters/{counter}/decrement', [CounterController::class, 'decrement'])->name('counters.decrement');

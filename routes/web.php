<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\MovieController;

Route::get('/admin/movies/create', [MovieController::class, 'create'])->name('movies.create');
Route::post('/admin/movies', [MovieController::class, 'store'])->name('movies.store');

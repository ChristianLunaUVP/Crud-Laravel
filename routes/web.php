<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CancionController;

Route::get('/', function () {
    return view('layout');
});

Route::resource('generos', 'App\Http\Controllers\GenerosController');
Route::resource('canciones', 'App\Http\Controllers\CancionesController');
Route::delete('/canciones/{id}/delete-image', [CancionesController::class, 'deleteImage']);
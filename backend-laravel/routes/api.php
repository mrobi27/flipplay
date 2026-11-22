<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;

Route::apiResource('media', MediaController::class);

Route::post(
    '/users/{user}/media/{medium}',
    [MediaController::class, 'addToCollection']
);

Route::delete(
    '/users/{user}/media/{medium}',
    [MediaController::class, 'removeFromCollection']
);


Route::get(
    '/users/{user}/collection',
    [MediaController::class, 'userCollection']
);

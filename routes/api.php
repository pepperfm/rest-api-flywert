<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [LoginController::class, 'auth']);
Route::post('/register', [RegisterController::class, 'register']);

Route::group([
    'middleware' => 'api.access'
], function () {
    // Статьи
    Route::apiResource('articles', ArticleController::class);

    // Пользователи
    Route::put('/update-user-data', [UserController::class, 'update']);
});


<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\ArticleController;

Route::middleware('api.access')->apiResource('articles', ArticleController::class);

Route::post('/login', [LoginController::class, 'auth']);
Route::post('/register', [RegisterController::class, 'register']);

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/token', [AuthController::class, 'token']);
Route::delete('auth/session', [AuthController::class, 'revokeSession']);
Route::get('auth/user', [AuthController::class, 'user'])->middleware('auth');

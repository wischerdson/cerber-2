<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandshakeController;
use App\Http\Controllers\SecretController;
use Illuminate\Support\Facades\Route;

Route::post('handshake', [HandshakeController::class, 'create']);

Route::post('auth/token', [AuthController::class, 'token']);
Route::delete('auth/session', [AuthController::class, 'revokeSession']);
Route::get('auth/user', [AuthController::class, 'user'])->middleware('auth');

Route::get('secrets', [SecretController::class, 'index']);
Route::post('secrets', [SecretController::class, 'create']);

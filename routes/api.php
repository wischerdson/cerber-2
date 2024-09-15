<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandshakeController;
use App\Http\Controllers\SecretController;
use Illuminate\Support\Facades\Route;

Route::post('handshake', [HandshakeController::class, 'create']);

Route::post('auth/token', [AuthController::class, 'token'])->middleware('encrypt-response');
Route::delete('auth/session', [AuthController::class, 'revokeSession']);

Route::middleware('auth')->group(function () {
	Route::get('auth/user', [AuthController::class, 'user']);

	Route::get('secrets', [SecretController::class, 'index'])->middleware('encrypt-response');
	Route::post('secrets', [SecretController::class, 'create']);
});

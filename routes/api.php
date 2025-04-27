<?php

use App\Http\Controllers\Aggregates\NodeAggregateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SecretGroupController;
use App\Http\Controllers\HandshakeController;
use App\Http\Controllers\NodeController;
use Illuminate\Support\Facades\Route;

Route::post('handshake', [HandshakeController::class, 'create']);

Route::post('auth/token', [AuthController::class, 'token'])->middleware('encrypt-response');
Route::delete('auth/session', [AuthController::class, 'revokeSession']);

Route::middleware('auth')->group(function () {
	Route::get('auth/user', [AuthController::class, 'user']);

	Route::apiResource('nodes', NodeController::class);
	Route::post('/nodes/batch', [NodeController::class, 'createBatch']);
	Route::put('/nodes/batch', [NodeController::class, 'updateBatch']);

	Route::get('secrets-breadcrumb', [SecretGroupController::class, 'breadcrumb'])->middleware('encrypt-response');

	Route::prefix('aggregates')->group(function () {
		Route::get('nodes', NodeAggregateController::class);
	});
});

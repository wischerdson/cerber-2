<?php

use App\Http\Controllers\Aggregates\DocumentGroupAggregateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SecretGroupController;
use App\Http\Controllers\HandshakeController;
use Illuminate\Support\Facades\Route;

Route::post('handshake', [HandshakeController::class, 'create']);

Route::post('auth/token', [AuthController::class, 'token'])->middleware('encrypt-response');
Route::delete('auth/session', [AuthController::class, 'revokeSession']);

Route::middleware('auth')->group(function () {
	Route::get('auth/user', [AuthController::class, 'user']);

	// Route::apiResource('documents', )

	// Route::apiResource('secrets', SecretController::class)->middleware('encrypt-response');
	// Route::apiResource('secret-groups', SecretGroupController::class)->middleware('encrypt-response');

	Route::get('secrets-breadcrumb', [SecretGroupController::class, 'breadcrumb'])->middleware('encrypt-response');

	Route::prefix('aggregates')->group(function () {
		Route::get('document-group', DocumentGroupAggregateController::class);
	});
});

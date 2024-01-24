<?php

namespace App\Facades;

use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string respondToAccessTokenRequest()
 * @method static void enableGrantType(\App\Services\Auth\GrantTypes\AbstractGrantType $grantType)
 * @method static \App\Models\Auth\Session currentSession()
 *
 * @see \App\Services\Auth\AuthService
 */
class Auth extends Facade
{
	protected static function getFacadeAccessor()
	{
		return AuthService::class;
	}
}

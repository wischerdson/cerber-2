<?php

namespace App\Facades;

use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string respondToAccessTokenRequest()
 * @method static void enableGrantType(\App\Services\Auth\GrantTypes\AbstractGrantType $grantType)
 * @method static \App\Models\Auth\Session currentSession()
 * @method static \App\Models\User|null user()
 * @method static \App\Services\Auth\GrantTypes\AbstractGrantType grantType(string $identifier)
 * @method static \Lcobucci\JWT\UnencryptedToken|null parseToken(string $rawToken, ?callable $beforeValidation = null)
 * @method static \App\Services\Auth\Guard apiGuard()
 * @method static \App\Models\Auth\Session|null findSessionByToken(\Lcobucci\JWT\UnencryptedToken $token)
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

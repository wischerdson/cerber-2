<?php

namespace App\Services\Auth\GrantTypes;

use App\Models\Auth\AbstractExtendedGrant;
use App\Models\Auth\Grant;
use App\Models\Auth\RefreshTokenGrant;
use App\Models\Auth\Session as AuthSession;
use App\Models\User;
use App\Services\Auth\AccessTokenFactory;
use App\Services\Auth\AuthService;
use App\Services\Auth\RefreshTokenFactory;
use App\Services\Auth\TokensPair;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

abstract class AbstractGrantType
{
	/**
	 * Return the grant identifier that can be used in matching up requests.
	 */
	abstract public static function getIdentifier(): string;

	abstract public function hasGrant(): bool;

	abstract public function respondToAccessTokenRequest(Request $request): TokensPair;

	abstract protected function createGrant(): AbstractExtendedGrant;

	public function canRespondToAccessTokenRequest(Request $request)
	{
		$request->validate([
			'grant_type' => 'required',
		]);

		return $request->grant_type === $this->getIdentifier();
	}

	public function createGrantFor(User|int $user): AbstractExtendedGrant
	{
		$userId = gettype($user) === 'object' ? $user->getKey() : $user;

		$grant = $this->createGrant();

		$baseGrant = new Grant();
		$baseGrant->user_id = $userId;

		$grant->baseGrant()->save($baseGrant);

		return $grant->setRelation('baseGrant', $baseGrant);
	}

	// public function updateGrantFor(User|int $user): AbstractExtendedGrant
	// {
	// 	$userId = gettype($user) === 'object' ? $user->getKey() : $user;
	// }

	protected function createRefreshTokenGrant(int $userId): RefreshTokenGrant
	{
		/** @var \App\Services\Auth\AuthService */
		$authService = Container::getInstance()->make(AuthService::class);

		return $authService->grantType('refresh_token')->createGrantFor($userId);
	}

	protected function makeTokensPair(AuthSession $session, RefreshTokenGrant $refreshTokenGrant): TokensPair
	{
		return new TokensPair(
			$this->makeAccessToken($session),
			$this->makeRefreshToken($session, $refreshTokenGrant)
		);
	}

	protected function makeAccessToken(AuthSession $session): AccessTokenFactory
	{
		$accessTokenFactory = new AccessTokenFactory();
		$accessTokenFactory->setAuthSession($session);

		return $accessTokenFactory;
	}

	protected function makeRefreshToken(AuthSession $session, RefreshTokenGrant $refreshTokenGrant): RefreshTokenFactory
	{
		$refreshTokenFactory = new RefreshTokenFactory();
		$refreshTokenFactory->setRefreshTokenGrant($refreshTokenGrant);
		$refreshTokenFactory->setAuthSession($session);

		return $refreshTokenFactory;
	}

	protected function createSession(Grant $grant, Request $request): AuthSession
	{
		$session = new AuthSession();
		$session->user_id = $grant->user_id;
		$session->user_agent = $request->header('User-Agent');
		$session->ip = $request->ip();

		$grant->authSessions()->save($session);

		return $session;
	}
}

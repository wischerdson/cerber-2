<?php

namespace App\Services\Auth\GrantTypes;

use App\Models\Auth\RefreshTokenGrant;
use App\Services\Auth\AuthService;
use App\Services\Auth\Exceptions\AuthCredentialsErrorException;
use App\Services\Auth\RefreshTokenFactory;
use App\Services\Auth\TokensPair;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Lcobucci\JWT\UnencryptedToken;

class RefreshTokenGrantType extends AbstractGrantType
{
	public static function getIdentifier(): string
	{
		return 'refresh_token';
	}

	public function canRespondToAccessTokenRequest(Request $request)
	{
		return (
			parent::canRespondToAccessTokenRequest($request) &&
			$request->validate([
				'refresh_token' => 'required'
			])
		);
	}

	/**
	 * @throws \App\Services\Auth\Exceptions\AuthCredentialsErrorException
	 */
	public function respondToAccessTokenRequest(Request $request): TokensPair
	{
		/** @var \App\Services\Auth\AuthService */
		$authService = Container::getInstance()->make(AuthService::class);

		if (
			(!$refreshToken = $authService->parseToken($request->refresh_token)) ||
			(!$session = $authService->findSessionByToken($refreshToken)) ||
			(!$grant = $this->findGrant($refreshToken))
		) {
			throw new AuthCredentialsErrorException();
		}

		$grant->expires_at = RefreshTokenFactory::getExpiresAt();
		$grant->saveWithNewCode();

		return $this->makeTokensPair($session, $grant);
	}

	public function hasGrant(): bool
	{
		return false;
	}

	protected function createGrant(): RefreshTokenGrant
	{
		$grant = new RefreshTokenGrant();
		$grant->expires_at = RefreshTokenFactory::getExpiresAt();
		$grant->saveWithNewCode();

		return $grant;
	}

	private function findGrant(UnencryptedToken $token): ?RefreshTokenGrant
	{
		$grantId = $token->claims()->get(RefreshTokenFactory::REFRESH_TOKEN_GRANT_ID_CLAIM);
		$grantCode = $token->claims()->get(RefreshTokenFactory::REFRESH_TOKEN_GRANT_CODE_CLAIM);

		return RefreshTokenGrant::query()
			->where('id', $grantId)
			->where('code', $grantCode)
			->first();
	}
}

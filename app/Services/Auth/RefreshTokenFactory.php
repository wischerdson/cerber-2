<?php

namespace App\Services\Auth;

use App\Models\Auth\RefreshTokenGrant;
use App\Models\Auth\Session as AuthSession;
use Illuminate\Support\Carbon;
use Lcobucci\JWT\UnencryptedToken;
use RuntimeException;

class RefreshTokenFactory extends TokenFactory
{
	public const REFRESH_TOKEN_GRANT_ID_CLAIM = 'id';

	public const REFRESH_TOKEN_GRANT_CODE_CLAIM = 'code';

	public static function getExpiresAt(): Carbon
	{
		return now()->addMonth();
	}

	public function setRefreshTokenGrant(RefreshTokenGrant $grant)
	{
		$this->jwtBuilder = $this->jwtBuilder
			->withClaim(self::REFRESH_TOKEN_GRANT_CODE_CLAIM, $grant->code)
			->withClaim(self::REFRESH_TOKEN_GRANT_ID_CLAIM, $grant->getKey());
	}

	public function setAuthSession(AuthSession $session)
	{
		$this->jwtBuilder = $this->jwtBuilder->withClaim(self::SESSION_ID_CLAIM, $session->getKey());
	}

	/**
	 * @throws \RuntimeException
	 */
	protected function checkToken(UnencryptedToken $token): UnencryptedToken
	{
		parent::checkToken($token);

		if (
			!$token->claims()->has(self::REFRESH_TOKEN_GRANT_ID_CLAIM) ||
			!$token->claims()->has(self::REFRESH_TOKEN_GRANT_CODE_CLAIM) ||
			!$token->claims()->has(self::SESSION_ID_CLAIM)
		) {
			throw new RuntimeException('Refresh token cannot be issued.');
		}

		return $token;
	}
}

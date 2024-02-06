<?php

namespace App\Services\Auth;

use App\Models\Auth\RefreshTokenGrant;
use App\Models\Auth\Session as AuthSession;
use Illuminate\Support\Carbon;
use Lcobucci\JWT\UnencryptedToken;
use RuntimeException;

class RefreshTokenFactory extends TokenFactory
{
	public const CLAIM_GRANT_ID = 'id';

	public const CLAIM_GRANT_CODE = 'code';

	public static function getExpiresAt(): Carbon
	{
		return now()->addMonth();
	}

	public function setRefreshTokenGrant(RefreshTokenGrant $grant)
	{
		$this->jwtBuilder = $this->jwtBuilder
			->withClaim(self::CLAIM_GRANT_CODE, $grant->code)
			->withClaim(self::CLAIM_GRANT_ID, $grant->getKey());
	}

	public function setAuthSession(AuthSession $session)
	{
		$this->jwtBuilder = $this->jwtBuilder->withClaim(self::CLAIM_SESSION_ID, $session->getKey());
	}

	/**
	 * @throws \RuntimeException
	 */
	protected function checkToken(UnencryptedToken $token): UnencryptedToken
	{
		parent::checkToken($token);

		if (
			!$token->claims()->has(self::CLAIM_GRANT_ID) ||
			!$token->claims()->has(self::CLAIM_GRANT_CODE) ||
			!$token->claims()->has(self::CLAIM_SESSION_ID)
		) {
			throw new RuntimeException('Refresh token cannot be issued.');
		}

		return $token;
	}
}

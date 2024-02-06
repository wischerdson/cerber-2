<?php

namespace App\Services\Auth;

use App\Models\Auth\Session as AuthSession;
use Illuminate\Support\Carbon;
use Lcobucci\JWT\UnencryptedToken;
use RuntimeException;

class AccessTokenFactory extends TokenFactory
{
	public static function getExpiresAt(): Carbon
	{
		return now()->addMinutes(30);
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

		if (!$token->claims()->has(self::CLAIM_SESSION_ID)) {
			throw new RuntimeException('Access token cannot be issued.');
		}

		return $token;
	}
}

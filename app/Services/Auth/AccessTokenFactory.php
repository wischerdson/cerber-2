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
		$this->jwtBuilder = $this->jwtBuilder->withClaim(self::SESSION_ID_CLAIM, $session->getKey());
	}

	/**
	 * @throws \RuntimeException
	 */
	protected function checkToken(UnencryptedToken $token): UnencryptedToken
	{
		parent::checkToken($token);

		if (!$token->claims()->has(self::SESSION_ID_CLAIM)) {
			throw new RuntimeException('Access token cannot be issued.');
		}

		return $token;
	}
}

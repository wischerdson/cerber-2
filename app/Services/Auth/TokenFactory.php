<?php

namespace App\Services\Auth;

use App\Models\Auth\Session as AuthSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder as JwtBuilder;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\UnencryptedToken;
use RuntimeException;

class TokenFactory
{
	public const SESSION_ID_CLAIM = 'ses';

	protected JwtBuilder $jwtBuilder;

	private bool $allowIndefiniteTokenTTL;

	public static function getSigningKey(): InMemory
	{
		$key = Str::replaceStart('base64:', '', config('app.key'));

		return InMemory::base64Encoded($key);
	}

	public function __construct()
	{
		$this->jwtBuilder = new JwtBuilder(
			new JoseEncoder(),
			ChainedFormatter::withUnixTimestampDates()
		);
		$this->jwtBuilder->issuedAt(now()->toDateTimeImmutable());

		$this->allowIndefiniteTokenTTL = config('auth.allow_indefinite_token_ttl', false);
	}

	public function setAuthSession(AuthSession $session)
	{
		$this->jwtBuilder->withClaim(self::SESSION_ID_CLAIM, $session->getKey());
	}

	/**
	 * Set time to live of the token
	 */
	public function expiresAt(Carbon $ttl)
	{
		$this->jwtBuilder->expiresAt($ttl->toDateTimeImmutable());
	}

	public function issueToken(): UnencryptedToken
	{
		return $this->checkToken(
			$this->jwtBuilder->getToken(new Sha256(), self::getSigningKey())
		);
	}

	/**
	 * @throws \RuntimeException
	 */
	protected function checkToken(UnencryptedToken $token): UnencryptedToken
	{
		if (
			!$token->claims()->has(RegisteredClaims::EXPIRATION_TIME &&
			!$this->allowIndefiniteTokenTTL
		)) {
			throw new RuntimeException('Token TTL is not set');
		}

		if (!$token->claims()->has(self::SESSION_ID_CLAIM)) {
			throw new RuntimeException('Token session ID is not set');
		}

		return $token;
	}
}

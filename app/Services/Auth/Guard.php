<?php

namespace App\Services\Auth;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\Exceptions\AccessTokenHasExpiredException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Http\Request;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token as JwtTokenContract;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator as JwtValidator;

class Guard implements AuthGuard
{
	use GuardHelpers;

	protected Request $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function user(): ?Authenticatable
	{
		if ($this->user) {
			return $this->user;
		}

		if (!$accessToken = $this->getTokenFromRequest()) {
			return null;
		}

		if (!$session = $this->findSession($accessToken)) {
			return null;
		}

		return $this->user = $session->user->withAuthSession($session);
	}

	public function currentSession(): ?AuthSession
	{
		/** @var \App\Models\User $user */
		if ($user = $this->user()) {
			return $user->currentAuthSession();
		}

		return null;
	}

	public function validate(array $credentials = []): bool
	{
		return true;
	}

	protected function getTokenFromRequest(): UnencryptedToken
	{
		$bearerToken = $this->request->bearerToken();

		$parser = new Parser(new JoseEncoder());

		try {
			$token = $parser->parse($bearerToken);
		} catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
			return null;
		}

		return $this->validateToken($token);
	}

	/**
	 * @throws \App\Services\Auth\Exceptions\AccessTokenHasExpiredException
	 */
	protected function validateToken(JwtTokenContract $token): ?JwtTokenContract
	{
		$validator = new JwtValidator();

		if ($token->isExpired(now())) {
			throw new AccessTokenHasExpiredException();
		}

		$signedWith = new SignedWith(new Sha256(), TokenFactory::getSigningKey());

		if (!$validator->validate($token, $signedWith)) {
			return null;
		}

		return $token;
	}

	protected function findSession(UnencryptedToken $token): ?AuthSession
	{
		if (!$sessionId = $token->claims()->get(TokenFactory::SESSION_ID_CLAIM)) {
			return null;
		}

		$session = AuthSession::query()
			->where('id', $sessionId)
			->where('revoked', false)
			->where('user_agent', $this->request->header('User-Agent'))
			->first();

		if (!$session) {
			return null;
		}

		return tap($session, function (AuthSession $session) {
			$session->forceFill(['last_seen_at' => now()])->save();
		});
	}
}

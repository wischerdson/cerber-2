<?php

namespace App\Services\Auth;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\Exceptions\AccessTokenHasExpiredException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Http\Request;
use Lcobucci\JWT\UnencryptedToken;

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

	protected function getTokenFromRequest(): ?UnencryptedToken
	{
		if (!$bearerToken = $this->request->bearerToken()) {
			return null;
		}

		return AuthService::parseToken($bearerToken, function (UnencryptedToken $token) {
			$token->isExpired(now()) && throw new AccessTokenHasExpiredException();
		});
	}

	protected function findSession(UnencryptedToken $token): ?AuthSession
	{
		$session = Container::getInstance()
			->make(AuthService::class)
			->findSessionByToken($token);

		if (!$session) {
			return null;
		}

		return tap($session, function (AuthSession $session) {
			$session->forceFill(['last_seen_at' => now()])->save();
		});
	}
}

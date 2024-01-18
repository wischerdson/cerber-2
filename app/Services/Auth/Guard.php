<?php

namespace App\Services\Auth;

use App\Models\Auth\PersonalAccessToken;
use App\Services\Auth\Exceptions\AccessTokenHasExpiredException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Http\Request;

class Guard implements AuthGuard
{
	use GuardHelpers;

	protected Request $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @throws \App\Services\Auth\Exceptions\AccessTokenHasExpiredException
	 */
	public function user(): ?Authenticatable
	{
		if ($this->user) {
			return $this->user;
		}

		if (!$accessToken = $this->findPersonalAccessToken()) {
			return null;
		}

		if ($accessToken->expires_at->isPast()) {
			throw new AccessTokenHasExpiredException();
		}

		$accessToken->forceFill(['last_used_at' => now()])->save();

		return $this->user = $accessToken->user->withAccessToken($accessToken);
	}

	public function validate(array $credentials = []): bool
	{
		return true;
	}

	protected function findPersonalAccessToken(): ?PersonalAccessToken
	{
		if (!$compositeToken = $this->getTokenFromRequest()) {
			return null;
		}

		[$id, $token] = $compositeToken;

		if ($model = PersonalAccessToken::find($id)) {
			return hash_equals($model->token, hash('crc32b', $token)) ? $model : null;
		}

		return null;
	}

	/**
	 * @return null|array{0: int, 1: string}
	 */
	protected function getTokenFromRequest(): ?array
	{
		$token = $this->request->bearerToken();

		if ($token && str_contains($token, '|')) {
			$exploded = explode('|', $token, 2);

			return ctype_digit($exploded[0]) && $exploded[1] ? $exploded : null;
		}

		return null;
	}
}

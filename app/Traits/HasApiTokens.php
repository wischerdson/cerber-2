<?php

namespace App\Traits;

use App\Models\Auth\PersonalAccessToken;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;

trait HasApiTokens
{
	protected PersonalAccessToken $accessToken;

	public function createToken(array $abilities = ['*'], DateTimeInterface $expiresAt = null): NewAccessToken
	{
		$token = $this->personalAccessTokens()->create([
			'token' => hash('sha256', $plainTextToken = Str::random(40)),
			'abilities' => $abilities,
			'expires_at' => $expiresAt,
		]);

		return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
	}

	public function accessTokens(): HasMany
	{
		return $this->hasMany(PersonalAccessToken::class, 'user_id');
	}

	public function currentAccessToken(): PersonalAccessToken
    {
        return $this->accessToken;
    }

	public function withAccessToken(PersonalAccessToken $accessToken): self
	{
		$this->accessToken = $accessToken;

		return $this;
	}
}

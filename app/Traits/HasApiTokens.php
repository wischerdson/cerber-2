<?php

namespace App\Traits;

use App\Models\Auth\PersonalAccessToken;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;

trait HasApiTokens
{
	use SanctumHasApiTokens;

	/**
	 * Create a new personal access token for the user.
	 */
	public function createToken(array $abilities = ['*'], DateTimeInterface $expiresAt = null): NewAccessToken
	{
		$token = $this->personalAccessTokens()->create([
			'token' => hash('sha256', $plainTextToken = Str::random(40)),
			'abilities' => $abilities,
			'expires_at' => $expiresAt,
		]);

		return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
	}

	/**
     * Get the access tokens that belong to model.
     */
	public function personalAccessTokens(): HasMany
	{
		return $this->hasMany(PersonalAccessToken::class, 'user_id');
	}
}

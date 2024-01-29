<?php

namespace App\Models\Auth;

use App\Services\Auth\GrantTypes\RefreshTokenGrantType;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $code Requires to confirm the one-time use
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class RefreshTokenGrant extends AbstractExtendedGrant
{
	const CREATED_AT = null;

	protected $table = 'auth_refresh_token_grants';

	protected $casts = [
		'expires_at' => 'timestamp',
		'updated_at' => 'timestamp'
	];

	public function getMorphClass(): string
	{
		return RefreshTokenGrantType::getIdentifier();
	}

	public function saveWithNewCode(): bool
	{
		do {
			/** @see \App\Mixins\Str */
			$code = Str::randHex(3);
		} while ($code === $this->code);

		$this->code = $code;

		return $this->save();
	}
}

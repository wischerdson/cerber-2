<?php

namespace App\Models\Auth;

use App\Services\Auth\GrantTypes\PasswordGrantType;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $password_hash
 * @property \App\Models\Auth\Grant $baseGrant
 * @property \Illuminate\Support\Carbon $password_changed_at
 */
class PasswordGrant extends AbstractExtendedGrant
{
	public $timestamps = false;

	protected $table = 'auth_password_grants';

	protected $casts = [
		'password_changed_at' => 'timestamp',
		'password' => 'encrypted'
	];

	public function getMorphClass(): string
	{
		return PasswordGrantType::getIdentifier();
	}

	protected static function booted()
	{
		$hashPassword = fn (self $model) => $model->password_hash = Hash::make($model->password);

		static::creating($hashPassword);
		static::updating(function ($model) use ($hashPassword) {
			if ($model->isDirty('password')) {
				$model->password_changed_at = now();
				$hashPassword($model);
			}
		});
	}
}

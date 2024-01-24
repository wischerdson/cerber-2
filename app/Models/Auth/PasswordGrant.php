<?php

namespace App\Models\Auth;

use App\Traits\ExtendedGrant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $password_hash
 * @property \App\Models\Auth\EntryPoint $baseEntryPoint
 * @property \Illuminate\Support\Carbon $password_changed_at
 */
class PasswordGrant extends Model
{
	use HasFactory, ExtendedGrant;

	public $timestamps = false;

	protected $table = 'auth_grant_passwords';

	protected $casts = [
		'password_changed_at' => 'timestamp',
		'password' => 'encrypted'
	];

	public function getMorphClass(): string
	{
		return PasswordGrant::getIdentifier();
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

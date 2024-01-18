<?php

namespace App\Models\Auth;

use App\Traits\ProviderEntryPoint;
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
class LoginProviderEntryPoint extends Model implements ProviderEntryPoint
{
	use HasFactory, ProviderEntryPoint;

	public $timestamps = false;

	protected $table = 'login_provider_entry_points';

	protected $casts = [
		'password_changed_at' => 'timestamp',
		'password' => 'encrypted'
	];

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

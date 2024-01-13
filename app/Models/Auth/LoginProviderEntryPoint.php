<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $password_hash
 * @property \Illuminate\Support\Carbon $password_changed_at
 */
class LoginProviderEntryPoint extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $table = 'login_provider_entry_points';

	protected $casts = [
		'password_changed_at' => 'timestamp',
		'password' => 'encrypted'
	];

	public function baseEntryPoint(): MorphOne
	{
		return $this->morphOne(EntryPoint::class, 'provider', 'provider', 'provider_entry_point_id');
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

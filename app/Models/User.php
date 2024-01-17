<?php

namespace App\Models;

use App\Models\Auth\EntryPoint;
use App\Traits\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $timezone
 * @property int|null $timezone_offset
 * @property bool $is_admin
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	const UPDATED_AT = null;

	protected $table = 'users';

	protected $hidden = ['is_admin'];

	protected static $unguarded = true;

	protected $casts = [
		'is_admin' => 'boolean',
		'created_at' => 'timestamp'
	];

	public function entryPoints(): HasMany
	{
		return $this->hasMany(EntryPoint::class, 'user_id');
	}

	public function asAdmin(): self
	{
		$this->is_admin = true;
		return $this;
	}
}

<?php

namespace App\Models;

use App\Models\Auth\Grant;
use App\Models\Auth\PasswordGrant;
use App\Models\Auth\Session as AuthSession;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
	use HasFactory, Notifiable;

	protected static $factory = UserFactory::class;

	const UPDATED_AT = null;

	protected $table = 'users';

	protected $hidden = ['is_admin'];

	protected AuthSession $authSession;

	protected $casts = [
		'is_admin' => 'boolean',
		'created_at' => 'timestamp'
	];

	public function sessions(): HasMany
	{
		return $this->hasMany(AuthSession::class, 'user_id');
	}

	public function asAdmin(): self
	{
		$this->is_admin = true;

		return $this;
	}

	public function withAuthSession(AuthSession $session): self
	{
		$this->authSession = $session;

		return $this;
	}

	public function currentAuthSession(): AuthSession
	{
		return $this->authSession;
	}

	public function grants(): HasMany
	{
		return $this->hasMany(Grant::class, 'user_id');
	}

	public function passwordGrant(): HasOne
	{
		$query = $this->grants()->getQuery()
			->whereHasMorph('extendedGrant', PasswordGrant::class)
			->with('extendedGrant');

		return $this->newHasOne($query, $this, 'user_id', $this->getKeyName());
	}

	public function secretGroups(): HasMany
	{
		return $this->hasMany(Group::class, 'user_id');
	}
}

<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $grant_type
 * @property int $grant_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\User $user
 */
class Grant extends Model
{
	use HasFactory;

	protected $table = 'auth_grants';

	protected $casts = [
		'is_active' => 'boolean'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function extendedGrant(): MorphTo
	{
		return $this->morphTo(type: 'grant_type', id: 'grant_id');
	}

	public function authSessions(): HasMany
	{
		return $this->hasMany(Session::class, 'grant_id');
	}
}

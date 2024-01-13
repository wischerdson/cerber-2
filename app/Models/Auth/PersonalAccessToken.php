<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $abilities
 * @property \Illuminate\Support\Carbon $last_used_at
 * @property \Illuminate\Support\Carbon $created_at
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $table = 'personal_access_tokens';

	protected static $unguarded = true;

	protected $casts = [
		'abilities' => 'json',
		'last_used_at' => 'timestamp',
		'created_at' => 'timestamp'
	];

	public function tokenable(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}

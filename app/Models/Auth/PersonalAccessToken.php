<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $access_token
 * @property string $refresh_token
 * @property \Illuminate\Support\Carbon $last_used_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon $created_at
 */
class PersonalAccessToken extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $table = 'personal_access_tokens';

	protected $casts = [
		'last_used_at' => 'timestamp',
		'expires_at' => 'timestamp',
		'created_at' => 'timestamp'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}

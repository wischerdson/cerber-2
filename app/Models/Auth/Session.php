<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $grant_id
 * @property string $user_agent
 * @property string $ip
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon $last_seen_at
 * @property \App\Models\User $user
 * @property \App\Models\Auth\Grant $grant
 */
class Session extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $table = 'auth_sessions';

	protected $casts = [
		'revoked' => 'boolean',
		'last_seen_at' => 'timestamp'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function grant(): BelongsTo
	{
		return $this->belongsTo(Grant::class, 'grant_id');
	}

	public function revoke(): self
	{
		$this->revoked = true;
		$this->save();

		return $this;
	}
}

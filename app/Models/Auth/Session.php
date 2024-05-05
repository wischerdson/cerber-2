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
 * @property string $server_private_key
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

	protected $hidden = [
		'server_private_key'
	];

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

	protected static function booted(): void
	{
		static::creating(function (self $session) {
			$privateKey = openssl_pkey_new([
				'private_key_bits' => 1024,
				'private_key_type' => OPENSSL_KEYTYPE_RSA,
			]);

			openssl_pkey_export($privateKey, $privateKeyExported);

			$session->server_private_key = $privateKeyExported;
		});
	}
}

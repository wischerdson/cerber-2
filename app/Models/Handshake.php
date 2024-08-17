<?php

namespace App\Models;

use App\Services\Encryption\RsaEncrypter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $uuid
 * @property string $server_private_key
 * @property string $client_public_key
 * @property \Illuminate\Support\Carbon $last_used_at
 * @property \Illuminate\Support\Carbon $created_at
 */
class Handshake extends Model
{
	use HasFactory, HasUuids;

	const UPDATED_AT = null;

	public readonly string $server_public_key;

	protected $table = 'handshakes';

	protected $primaryKey = 'uuid';

	protected $casts = [
		'last_used_at' => 'timestamp',
		'created_at' => 'timestamp'
	];

	public function touch($attribute = null)
	{
		return parent::touch($attribute ?: 'last_used_at');
	}

	protected static function booted(): void
	{
		parent::booted();

		static::creating(function (self $handshake) {
			[$publicKey, $privateKey] = RsaEncrypter::createKeyPair();

			$handshake->server_private_key = $privateKey;
			$handshake->server_public_key = $publicKey;
			$handshake->last_used_at = now();
		});
	}
}

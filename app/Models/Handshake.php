<?php

namespace App\Models;

use App\Traits\PrimaryUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $uuid
 * @property string $server_private_key
 * @property string $client_public_key
 * @property \Illuminate\Support\Carbon $last_used_at
 * @property \Illuminate\Support\Carbon $created_at
 */
class Handshake extends Model
{
	use HasFactory, PrimaryUuid;

	const UPDATED_AT = null;

	public readonly string $server_public_key;

	protected $table = 'handshakes';

	protected $casts = [
		'last_used_at' => 'timestamp',
		'created_at' => 'timestamp'
	];

	protected static function booted(): void
	{
		parent::booted();

		static::creating(function (self $handshake) {
			$privateKey = openssl_pkey_new([
				'private_key_bits' => 1024,
				'private_key_type' => OPENSSL_KEYTYPE_RSA,
			]);

			openssl_pkey_export($privateKey, $privateKeyExported);
			$publicKeyDetails = openssl_pkey_get_details($privateKey);

			$handshake->server_private_key = $privateKeyExported;
			$handshake->server_public_key = $publicKeyDetails['key'];
			$handshake->last_used_at = now();
		});
	}
}

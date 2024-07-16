<?php

namespace App\Services;

use App\Exceptions\HandshakeNotFound;
use App\Models\Handshake;
use Illuminate\Http\Request;

class RsaEncryption
{
	public const HTTP_HEADER_HANDSHAKE_ID = 'X-Handshake-ID';

	public readonly ?Handshake $handshake;

	public function __construct(protected Request $request)
	{
		if ($handshakeId = $request->header(self::HTTP_HEADER_HANDSHAKE_ID)) {
			$this->handshake = Handshake::find($handshakeId);
		}
	}

	public function hasHandshake(): bool
	{
		return isset($this->handshake);
	}

	/**
	 * @throws \App\Exceptions\HandshakeNotFound
	 */
	public function throwIfHandshakeIsMissing()
	{
		if (!isset($this->handshake)) {
			throw new HandshakeNotFound();
		}
	}

	/**
	 * @return string base64 encrypted representation
	 */
	public function encrypt(string $plaintext): string
	{
		$this->throwIfHandshakeIsMissing();

		$this->handshake->touch();

		openssl_public_encrypt(
			$plaintext,
			$encrypted,
			$this->handshake->client_public_key,
			OPENSSL_PKCS1_OAEP_PADDING
		);

		return base64_encode($encrypted);
	}

	public function decrypt(string $base64): string
	{
		$this->throwIfHandshakeIsMissing();

		$this->handshake->touch();

		openssl_private_decrypt(
			base64_decode($base64),
			$decrypted,
			$this->handshake->server_private_key,
			OPENSSL_PKCS1_OAEP_PADDING
		);

		return $decrypted;
	}
}

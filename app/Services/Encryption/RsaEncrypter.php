<?php

namespace App\Services\Encryption;

use App\Models\Handshake;

class RsaEncrypter
{
	public function __construct(protected Handshake $handshake)
	{
		//
	}

	public static function createKeyPair()
	{
		$privateKey = openssl_pkey_new([
			'private_key_bits' => 1024,
			'private_key_type' => OPENSSL_KEYTYPE_RSA,
		]);

		openssl_pkey_export($privateKey, $privateKeyExported);
		$publicKeyDetails = openssl_pkey_get_details($privateKey);

		return [$publicKeyDetails['key'], $privateKeyExported];
	}

	/**
	 * @return string base64 encrypted representation
	 */
	public function encrypt(string $plaintext): string
	{
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

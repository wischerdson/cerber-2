<?php

namespace App\Services\Encryption;

use App\Models\Handshake;
use App\Services\Encryption\Exceptions\InvalidAesKeyException;
use App\Services\Encryption\Exceptions\HandshakeNotFoundException;
use Illuminate\Encryption\Encrypter as AesEncrypter;
use Illuminate\Http\Request;
use RuntimeException;

class RequestEncrypter
{
	public const HTTP_HEADER_HANDSHAKE_ID = 'X-Handshake-ID';

	public const HTTP_HEADER_KEY = 'X-Key';

	public static string $cipher = 'aes-128-gcm';

	protected AesEncrypter $aesEncrypter;

	protected RsaEncrypter $rsaEncrypter;

	public function __construct(protected Request $request)
	{
		//
	}

	/**
	 * @return string base64 encrypted representation
	 */
	public function encrypt(string $payload): string
	{
		return $this->getAesEncrypter()->encryptString($payload);
	}

	public function decrypt(string $payload): string
	{
		return $this->getAesEncrypter()->decryptString($payload);
	}

	public function setAesEncrypter(AesEncrypter $encrypter): void
	{
		$this->aesEncrypter = $encrypter;
	}

	public function getAesEncrypter(): AesEncrypter
	{
		if (isset($this->aesEncrypter)) {
			return $this->aesEncrypter;
		}

		if (!$encryptedKey = $this->request->header(self::HTTP_HEADER_KEY)) {
			throw new InvalidAesKeyException();
		}

		$key = $this->getRsaEncrypter()->decrypt($encryptedKey);

		try {
			return $this->aesEncrypter = new AesEncrypter($key, self::$cipher);
		} catch (RuntimeException $e) {
			// @todo set logger
			throw new InvalidAesKeyException();
		}
	}

	/**
	 * @throws \App\Exceptions\HandshakeNotFound
	 */
	public function getRsaEncrypter(): RsaEncrypter
	{
		if (isset($this->rsaEncrypter)) {
			return $this->rsaEncrypter;
		}

		$handshakeId = $this->request->header(self::HTTP_HEADER_HANDSHAKE_ID);

		if (!$handshakeId || !$handshake = Handshake::find($handshakeId)) {
			throw new HandshakeNotFoundException();
		}

		return $this->rsaEncrypter = new RsaEncrypter($handshake);
	}
}

<?php

namespace App\Exceptions;

class RsaException extends BadRequestException
{
	public static function invalidPublicKey()
	{
		return self::message('Body content must be a valid rsa public key')
			->reason('invalid_public_key');
	}
}

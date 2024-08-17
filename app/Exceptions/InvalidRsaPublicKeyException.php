<?php

namespace App\Exceptions;

class InvalidRsaPublicKeyException extends BadRequestException
{
	public ?string $errorReason = 'invalid_public_key';

	public ?string $errorMessage = 'Body content must be a valid rsa public key';
}

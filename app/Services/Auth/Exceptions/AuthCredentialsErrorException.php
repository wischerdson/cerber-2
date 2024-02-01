<?php

namespace App\Services\Auth\Exceptions;

use App\Exceptions\BadRequestException;

class AuthCredentialsErrorException extends BadRequestException
{
	public int $statusCode = 401;
}

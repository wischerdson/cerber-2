<?php

namespace App\Services\Auth\Exceptions;

use App\Exceptions\BadRequestException;

class AccessTokenHasExpiredException extends BadRequestException
{
	public int $statusCode = 401;
}

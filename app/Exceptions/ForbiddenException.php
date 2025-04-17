<?php

namespace App\Exceptions;

class ForbiddenException extends BadRequestException
{
	public int $httpCode = 403;
}

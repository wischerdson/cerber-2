<?php

namespace App\Exceptions;

class ForbiddenException extends BadRequestException
{
	public int $statusCode = 403;
}

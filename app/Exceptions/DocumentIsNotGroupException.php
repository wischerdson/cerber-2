<?php

namespace App\Exceptions;

class DocumentIsNotGroupException extends BadRequestException
{
	public ?string $errorMessage = 'Document is not a group';
}

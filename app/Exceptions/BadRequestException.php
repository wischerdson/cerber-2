<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BadRequestException extends Exception
{
	public ?string $errorReason = null;

	public ?string $errorMessage = null;

	public ?array $errorDetails = null;

	public int $statusCode = 422;

	public function report(): void
	{
	}

	/**
	 * Render the exception as an HTTP response.
	 */
	public function render(): JsonResponse
	{
		$responseData = ['error_reason' => $this->errorReason ?: $this->guessErrorReason()];

		if ($this->errorMessage !== null) {
			$responseData['message'] = $this->errorMessage;
		}

		if ($this->errorDetails !== null) {
			$responseData['details'] = $this->errorDetails;
		}

		return response()->json($responseData, $this->statusCode);
	}

	private function guessErrorReason()
	{
		$exploded = explode('\\', static::class);
		$className = array_pop($exploded);

		return Str::snake(preg_replace("/Exception$/", '', $className));
	}
}

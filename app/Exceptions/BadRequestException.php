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

		if (($message = $this->getErrorMessage()) !== null) {
			$responseData['message'] = $message;
		}

		if (($details = $this->getErrorDetails()) !== null) {
			$responseData['details'] = $details;
		}

		return response()->json($responseData, $this->statusCode);
	}

	protected function getErrorMessage()
	{
		return $this->errorMessage;
	}

	protected function getErrorDetails()
	{
		return $this->errorDetails;
	}

	private function guessErrorReason()
	{
		$exploded = explode('\\', static::class);
		$className = array_pop($exploded);

		return Str::snake(preg_replace("/Exception$/", '', $className));
	}
}

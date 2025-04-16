<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;

class BadRequestException extends Exception
{
	public ?string $errorReason = null;

	public ?array $errorDetails = null;

	public function __construct(
		string $message = '',
		int $code = 422,
		?Throwable $previous = null
	)
	{
		parent::__construct($message, $code, $previous);
	}

	public function report(): void
	{
	}

	/**
	 * Render the exception as an HTTP response.
	 */
	public function render(): JsonResponse
	{
		$responseData = [
			'status' => 'error',
			'error_reason' => $this->errorReason ?: $this->guessErrorReason()
		];

		if ($message = $this->getErrorMessage()) {
			$responseData['message'] = $message;
		}

		if (($details = $this->getErrorDetails()) !== null) {
			$responseData['details'] = $details;
		}

		return response()->json($responseData, $this->getCode());
	}

	protected function getErrorMessage(): ?string
	{
		return isset($this->message) ? $this->message : null;
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

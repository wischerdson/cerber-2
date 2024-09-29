<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretPreviewResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		if (is_null($this->resource)) {
            return [];
        }

		return collect($this->resource)
			->only(['id', 'name', 'is_uptodate', 'client_code', 'created_at', 'updated_at'])
			->toArray();
	}
}

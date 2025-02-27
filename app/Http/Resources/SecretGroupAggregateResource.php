<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretGroupAggregateResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$data = parent::toArray($request);
		$group = $data;

		unset($group['children'], $group['secrets'], $group['parent_groups']);

		return [
			'current_group' => $group,
			'children_groups' => $data['children'],
			'parent_groups' => $data['parent_groups'],
			'secrets' => $data['secrets']
		];
	}
}

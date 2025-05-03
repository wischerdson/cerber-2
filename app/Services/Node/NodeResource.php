<?php

namespace App\Services\Node;

use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class NodeResource extends JsonResource
{
	public ?Node $current = null;

	/** @var \App\Models\Node[] */
	public array $parents = [];

	/** @var \App\Models\Node[] */
	public array $descendants = [];

	public function __construct()
	{

	}

	public function setCurrentAndDescendants(Node $current): self
	{
		$current->loadMissing('descendants');

		$this->current = $current;
		$this->descendants = $current->descendants->toArray();

		return $this;
	}

	/**
	 * @param \App\Models\Node[] | \Illuminate\Support\Collection<\App\Models\Node> $descendants
	 */
	public function setDescendants(Collection|array $descendants): self
	{
		$this->descendants = $descendants instanceof Collection ?
			$descendants->toArray() :
			$descendants;

		return $this;
	}

	/**
	 * @param \App\Models\Node[] | \Illuminate\Support\Collection<\App\Models\Node> $parents
	 */
	public function setParents(Collection|array $parents): self
	{
		$this->parents = $parents instanceof Collection ?
			$parents->toArray() :
			$parents;

		return $this;
	}

	public function toArray(Request $request): array
	{
		return [
			'parents' => $this->parents,
			'current' => $this->current ? $this->current->except('descendants') : null,
			'descendants' => $this->descendants
		];
	}
}

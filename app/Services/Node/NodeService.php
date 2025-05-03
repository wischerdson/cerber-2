<?php

namespace App\Services\Node;

use App\Exceptions\NodeTroublesException;
use App\Models\Node;
use Illuminate\Database\Eloquent\Collection;

class NodeService
{
	public function getNodeResourceByChain(?array $chain)
	{
		if (!$chain) {
			return NodeResource::make()->setDescendants(
				$this->getRootNodes()
			);
		}

		$nodesByChain = $this->getNodesByChainOfAliases($chain);

		return NodeResource::make()
			->setCurrentAndDescendants($nodesByChain->pop())
			->setParents($nodesByChain);
	}

	public function getRootNodes(): Collection
	{
		return Node::query()->whereNull('parent_id')->get();
	}

	/**
	 * @throws \App\Exceptions\NodeTroublesException
	 */
	public function getNodesByChainOfAliases(array $aliasChain): Collection
	{
		$nodes = Node::query()->whereIn('alias', $aliasChain)
			->orderByRaw('FIELD(alias, "' . implode('","', $aliasChain) . '")')
			->get();

		if ($nodes->count() !== count($aliasChain)) {
			throw NodeTroublesException::nodeChainIsIncorrect();
		}

		$parentId = null;

		foreach ($nodes as $node) {
			if ($node->parent_id === $parentId) {
				$parentId = $node->id;
			} else {
				throw NodeTroublesException::nodeChainIsIncorrect();
			}
		}

		return $nodes;
	}
}

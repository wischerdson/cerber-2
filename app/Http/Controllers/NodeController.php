<?php

namespace App\Http\Controllers;

use App\Models\DocumentField;
use App\Models\Node;
use App\Services\Node\NodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NodeController
{
	public function index(NodeService $service, Request $request)
	{
		$chain = [];

		if ($chainAsStr = $request->chain) {
			$chain = explode(',', $chainAsStr);
		}

		return $service->getNodeResourceByChain($chain);
	}

	public function show(int $secretId)
	{
		return Node::query()
			->where('id', $secretId)
			->with('fields')
			->firstOrFail()
			->toArray();
	}

	public function createBatch(Request $request)
	{
		$nodes = [];

		DB::transaction(function () use ($request, &$nodes) {
			foreach ($request->all() as $nodeData) {
				$node = Node::create($nodeData);
				$node->client_code = @$nodeData['client_code'];

				if ($node->type === 'document') {
					foreach ($nodeData['fields'] as $fieldData) {
						$node->document_fields()->save(
							new DocumentField($fieldData)
						);
					}
				}

				$nodes[] = $node;
			}
		});

		return $nodes;
	}

	public function updateBatch(Request $request)
	{

	}
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\NodeNotFoundException;
use App\Models\DocumentField;
use App\Models\Node;
use Illuminate\Http\Request;

class NodeController
{
	public function index()
	{
		return Node::all();
	}

	public function show(int $secretId)
	{
		return Node::query()
			->where('id', $secretId)
			->with('fields')
			->firstOrFail()
			->toArray();
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => ['required', 'string'],
			'fields' => ['prohibited_if:is_group,true', 'present', 'array'],
			'fields.*.label' => ['required', 'string'],
			'fields.*.short_description' => ['required', 'string'],
		]);

		$node = Node::make($request->only('type', 'name', 'notes'));

		if ($parentId = $request->parent_id) {
			$parentNode = Node::find($parentId);

			if (!$parentNode || $parentNode->type !== 'group') {
				throw new NodeNotFoundException();
			}
		}

		$node->save();

		if ($node->type === 'document') {
			$fields = [];

			foreach (array_values($request->fields) as $field) {
				$fieldModel = new DocumentField(
					collect($field)
						->only('label', 'short_description', 'multiline', 'secure', 'sort')
						->all()
				);
				$fieldModel->value = $field['value'];
				$field[] = $fieldModel;
			}

			$node->fields()->saveMany($fields);
		}

		return $node;
	}
}

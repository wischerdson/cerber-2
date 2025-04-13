<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Facades\Auth;
use App\Models\Document;
use App\Models\DocumentField;
use Illuminate\Http\Request;

class DocumentController
{
	public function index()
	{
		return Document::all();
	}

	public function show(int $secretId)
	{
		return Document::query()
			->where('id', $secretId)
			->with('fields')
			->firstOrFail()
			->toArray();
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => ['required', 'string'],
			'is_group' => ['required', 'boolean'],
			'fields' => ['prohibited_if:is_group,true', 'present', 'array'],
			'fields.*.label' => ['required', 'string'],
			'fields.*.short_description' => ['required', 'string'],
		]);

		$user = Auth::user();

		if ($parentId = $request->parent_id) {
			$parentDocuement = Document::findOrFail($parentId);

			if ($parentDocuement->user_id !== $user->id) {
				throw new ForbiddenException();
			}
		}

		$document = Document::create($request->only('name', 'notes', 'is_group', 'parent_id'));

		if ($document->is_group) {
			return $document;
		}

		foreach (array_values($request->fields) as $i => $field) {
			$fieldModel = new DocumentField(
				collect($field)
					->only('label', 'short_description', 'multiline', 'secure', 'sort')
					->all()
			);
			$fieldModel->value = $field['value'];

			$document->fields()->save($fieldModel);
		}

		return $document;
	}
}

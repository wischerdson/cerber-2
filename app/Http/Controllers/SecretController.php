<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use App\Models\SecretField;
use Illuminate\Http\Request;

class SecretController extends Controller
{
	public function index()
	{
		$secrets = Secret::with('fields')->get();

		return $secrets;
	}

	public function create(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'fields' => ['required', 'array'],
			'fields.*.label' => 'required',
			'fields.*.short_description' => 'nullable|string|max:140',
		]);

		$secret = Secret::create($request->only('name', 'notes'));

		foreach (array_values($request->fields) as $i => $field) {
			$fieldModel = new SecretField(
				collect($field)
					->only('label', 'short_description', 'multiline', 'secure', 'sort')
					->all()
			);
			$fieldModel->value = $field['value'];

			$secret->fields()->save($fieldModel);
		}
	}
}

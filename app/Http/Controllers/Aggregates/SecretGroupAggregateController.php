<?php

namespace App\Http\Controllers\Aggregates;

use App\Models\SecretGroup;
use Illuminate\Http\Request;

class SecretGroupAggregateController
{
	public function __invoke(Request $request)
	{
		$request->validate([
			'group_alias' => 'required|string'
		]);

		$group = SecretGroup::query()
			->forCurrentUser()
			->where('alias', $request->group_alias)
			->with('children', 'secrets.fields')
			->firstOrFail();

		dd($group->toArray());
	}
}

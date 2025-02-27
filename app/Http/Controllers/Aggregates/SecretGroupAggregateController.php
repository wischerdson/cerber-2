<?php

namespace App\Http\Controllers\Aggregates;

use App\Http\Resources\SecretGroupAggregateResource;
use App\Models\SecretGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
			->with('children', 'secrets')
			->firstOrFail();

		$group->parent_groups = $this->fetchParentGroups($request->group_alias);

		return SecretGroupAggregateResource::make($group);
	}

	private function fetchParentGroups(string $groupAlias): Collection
	{
		$secretGroupTable = (new SecretGroup())->getTable();

		$startingGroupQuery = DB::query()->select('*')->from($secretGroupTable)->where('alias', DB::raw(':group_alias'));
		$parentGroupQuery = DB::query()->select('parent_group.*')->from('secret_groups', 'parent_group')
			->join('groups_breadcrumb', 'parent_group.id', '=', 'groups_breadcrumb.parent_id');

		$groups = DB::select(
			"WITH RECURSIVE groups_breadcrumb as (
				{$startingGroupQuery->toRawSql()}
				union all
				{$parentGroupQuery->toRawSql()}
			) SELECT * FROM `groups_breadcrumb` ORDER BY `parent_id` ASC",
			['group_alias' => $groupAlias]
		);

		return collect($groups)->map(fn ($group) => (new SecretGroup())->forceFill((array) $group));
	}
}

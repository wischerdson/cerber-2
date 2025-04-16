<?php

namespace App\Http\Controllers\Aggregates;

use App\Exceptions\BadRequestException;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NodeAggregateController
{
	public function __invoke(Request $request)
	{
		$request->validate([
			'id' => ['nullable', 'numeric'],
			'alias' => ['nullable', 'string', 'max:255']
		]);

		if (!$request->id && !$request->alias) {
			throw new BadRequestException('Either the ID or alias of the node is required');
		}

		$node = Node::query()
			->when(
				$request->id,
				fn ($query, $id) => $query->whereKey($id),
				fn ($query) => $query->where('alias', $request->alias)
			)
			->whereTypeIsGroup()
			->firstOr(
				fn () => throw new BadRequestException('Node of type group by ' . ($request->id ?: $request->alias) . ' not found', 404))
			;

		$node->loadMissing('descendants');

		return [
			'parents' => $node->parent_id ? $this->fetchParents($node->parent_id) : [],
			'current' => collect($node)->except('descendants'),
			'descendants' => $node->descendants
		];
	}

	private function fetchParents(int $parentId): Collection
	{
		$documentTable = (new Node())->getTable();

		$startingGroupQuery = DB::query()->select('*')->from($documentTable)->where('id', DB::raw(':parent_id'));
		$parentGroupQuery = DB::query()->select('parent.*')->from($documentTable, 'parent')
			->join('groups_breadcrumb', 'parent.id', '=', 'groups_breadcrumb.parent_id');

		$groups = DB::select(
			"WITH RECURSIVE groups_breadcrumb as (
				{$startingGroupQuery->toRawSql()}
				union all
				{$parentGroupQuery->toRawSql()}
			) SELECT * FROM `groups_breadcrumb` ORDER BY `parent_id` ASC",
			['parent_id' => $parentId]
		);

		return collect($groups)->map(fn ($group) => (new Node())->forceFill((array) $group));
	}
}

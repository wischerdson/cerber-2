<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Facades\Auth;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
	public function index(Request $request)
	{
		if (
			(!$parentId = $request->parent_id) &&
			(!$parentAlias = $request->parent_alias)
		) {
			return Group::query()
				->forCurrentUser()
				->where('parent_id', null)
				->get();
		}

		if (!$parentId) {
			$parentId = Group::query()
				->forCurrentUser()
				->findBySlug($parentAlias)
				->firstOrFail()
				->id;
		}

		return Group::query()
			->forCurrentUser()
			->where('parent_id', $parentId)
			->get();
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'string|max:255'
		]);

		$user = Auth::user();

		if ($groupParentId = $request->parent_id) {
			$parentGroup = Group::findOrFail($groupParentId);

			if ($parentGroup->user_id !== $user->id) {
				throw new ForbiddenException();
			}
		}

		$group = new Group($request->only('name', 'description'));
		$group->parent_id = $groupParentId;

		['id' => $id] = $user->secretGroups()->save($group);

		return response()->json(Group::find($id), 201);
	}

	public function show(int|string $groupId)
	{
		$group = Group::findOrFail($groupId);

		if ($group->user_id !== Auth::user()->id) {
			throw new ForbiddenException();
		}

		return $group;
	}

	public function update(int|string $groupId, Request $request)
	{
		$request->validate([
			'name' => 'string|max:255',
			'description' => 'string|max:255'
		]);

		$group = Group::findOrFail($groupId);

		if ($group->user_id !== Auth::user()->id) {
			throw new ForbiddenException();
		}

		$group->fill($request->only('name', 'description'));
		$group->save();
	}

	public function destroy(int|string $groupId)
	{
		$group = Group::findOrFail($groupId);

		if ($group->user_id !== Auth::user()->id) {
			throw new ForbiddenException();
		}

		$group->deleted_at = now();
		$group->save();
	}
}

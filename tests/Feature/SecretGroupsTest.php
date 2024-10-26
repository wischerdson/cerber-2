<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SecretGroupsTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function restrict_without_authorization(): void
	{
		$this->getJson('/groups')->assertUnauthenticated();
		$this->postJson('/groups')->assertUnauthenticated();
		$this->putJson('/groups/1')->assertUnauthenticated();
		$this->patchJson('/groups/1')->assertUnauthenticated();
		$this->deleteJson('/groups/1')->assertUnauthenticated();
	}

	/**
	 * Can the user be able to get the first-level groups (spaces) that he has created
	 *
	 * @test
	 */
	public function can_user_get_his_spaces(): void
	{
		Group::factory()->create();

		$this->actingAs($user = self::createUser())
			->getJson('/groups')
			->assertOk()
			->assertJson(fn (AssertableJson $json) =>
				$json->count(0)
			);

		$group = Group::factory()->for($user, 'user')->create();

		$this->actingAs($user)
			->getJson('/groups')
			->assertOk()
			->assertJson(fn (AssertableJson $json) =>
				$json->count(1)->has(0, fn (AssertableJson $json) =>
					$json->hasAll(
						'id', 'user_id', 'name', 'description', 'alias', 'parent_id', 'created_at',
						'deleted_at'
					)->where('id', $group->id)
				)
			);
	}

	/**
	 * @test
	 */
	public function can_user_get_group_details()
	{
		$this->actingAs($user = self::createUser())
			->getJson("/groups/nonExistentId")
			->assertNotFound();

		$groupWithoutUser = Group::factory()->create();

		$this->actingAs($user)
			->getJson("/groups/{$groupWithoutUser->id}")
			->assertNotFound();

		$group = Group::factory()->for($user, 'user')->create();

		$this->actingAs($user)
			->getJson("/groups/{$group->id}")
			->assertOk()
			->assertJson(fn (AssertableJson $json) =>
				$json->hasAll(
					'id', 'user_id', 'name', 'description', 'alias', 'parent_id', 'created_at',
					'deleted_at'
				)->where('id', $group->id)
			);
	}

	/**
	 * @test
	 */
	public function can_user_create_first_level_group()
	{
		$this->actingAs($user = self::createUser())
			->postJson('/groups', [])
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->has('details.name')
					->etc()
			);

		$group = $this->actingAs($user)
			->postJson('/groups', ['name' => 'Some first-level group'])
			->assertCreated()
			->assertJson(fn (AssertableJson $json) =>
				$json->hasAll(
					'id', 'user_id', 'name', 'description', 'alias', 'parent_id', 'created_at',
					'deleted_at'
				)
			)->collect();

		$this->assertDatabaseHas('groups', [
			'id' => $group->get('id'),
			'user_id' => $user->id,
			'name' => 'Some first-level group',
			'description' => null,
			'alias' => 'some-first-level-group',
			'parent_id' => null
		]);
	}

	/**
	 * @test
	 */
	public function can_user_create_subgroups()
	{
		$someoneElsesGroup = Group::factory()->create();

		$this->actingAs($user = self::createUser())
			->postJson('/groups', [
				'parent_id' => $someoneElsesGroup->id,
				'name' => "Some subgroup with parent \"{$someoneElsesGroup->name}\""
			])
			->assertStatus(403)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')
					->etc()
			);

		$group = Group::factory()->for($user, 'user')->create();

		$group = $this->actingAs($user)
			->postJson('/groups', [
				'parent_id' => $someoneElsesGroup->id,
				'name' => "Some subgroup with parent \"{$someoneElsesGroup->name}\""
			])
			->assertCreated()
			->assertJson(fn (AssertableJson $json) =>
				$json->hasAll(
					'id', 'user_id', 'name', 'description', 'alias', 'parent_id', 'created_at',
					'deleted_at'
				)
			)->collect();

		$this->assertDatabaseHas('groups', [
			'id' => $group->get('id'),
			'user_id' => $user->id,
			'name' => 'Some first-level group',
			'description' => null,
			'alias' => 'some-first-level-group',
			'parent_id' => null
		]);
	}
}

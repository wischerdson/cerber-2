<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SecretGroupsTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Проверяем, что все маршруты по работе с группами закрыты авторизацией
	 *
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
		// Создаем случайную группу, не привязанную к конкретному пользователю
		Group::factory()->create();

		// ... и убеждаемся, что пользователю, запросившему группы первого уровня (пространства),
		// отдается пустой массив, так как на его имя групп нет
		$this->actingAs($user = self::createUser())
			->getJson('/groups')
			->assertOk()
			->assertJsonIsArray()
			->assertJson(fn (AssertableJson $json) =>
				$json->count(0)
			);

		// Создаем группу от лица конкретного пользователя
		$group = Group::factory()->for($user, 'user')->create();

		// ... и убеждаемся, что ему пришел массив с 1 элементом с определенным набором свойств.
		// ID группы, пришедший в ответе должен совпадать с ID группы, которая была только что
		// создана выше
		$this->actingAs($user)
			->getJson('/groups')
			->assertOk()
			->assertJsonIsArray()
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
		// Проверяем, что на несуществующий ID группы придет ответ "Not Found"
		$this->actingAs($user = self::createUser())
			->getJson("/groups/nonExistentId")
			->assertNotFound();

		// Создаем случайную группу, не привязанную к конкретному пользователю
		$groupWithoutUser = Group::factory()->create();

		// ... и убеждаемся, что пользователю, запросившему детали не своей группы,
		// возвращается ошибка Forbidden
		$this->actingAs($user)
			->getJson("/groups/{$groupWithoutUser->id}")
			->assertForbidden();

		// Создаем группу от лица конкретного пользователя
		$group = Group::factory()->for($user, 'user')->create();

		// ... и убеждаемся, что ему пришел объект с определенным набором свойств и
		// ID группы, пришедший в ответе должен совпадать с ID группы, которая была только что
		// создана выше
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
			->assertJsonIsObject()
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
			->assertForbidden()
			->assertJsonIsObject()
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')->etc()
			);

		$parentGroup = Group::factory()->for($user, 'user')->create();

		$group = $this->actingAs($user)
			->postJson('/groups', [
				'parent_id' => $parentGroup->id,
				'name' => "Some subgroup with parent",
				'description' => "Group description"
			])
			->assertCreated()
			->assertJsonIsObject()
			->assertJson(fn (AssertableJson $json) =>
				$json->hasAll(
					'id', 'user_id', 'name', 'description', 'alias', 'parent_id', 'created_at',
					'deleted_at'
				)
			)->collect();

		$this->assertDatabaseHas('groups', [
			'id' => $group->get('id'),
			'user_id' => $user->id,
			'name' => 'Some subgroup with parent',
			'description' => 'Group description',
			'alias' => 'some-subgroup-with-parent',
			'parent_id' => $parentGroup->id
		]);
	}

	/**
	 * @test
	 */
	public function can_user_update_group()
	{
		$this->actingAs($user = self::createUser())
			->patchJson("/groups/non-existent-group", [])
			->assertNotFound();

		$someoneElsesGroup = Group::factory()->create();

		$this->actingAs($user)
			->patchJson("/groups/{$someoneElsesGroup->id}", [
				'name' => '123'
			])
			->assertForbidden()
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')->etc()
			);

		$group = Group::factory()->for($user, 'user')->create();

		$this->actingAs($user)
			->patchJson("/groups/{$group->id}", ['name' => '123'])
			->assertOk()
			->assertContent('');

		$this->assertDatabaseHas('groups', [
			'id' => $group->id,
			'user_id' => $user->id,
			'name' => '123',
			'alias' => '123',
			'parent_id' => null
		]);
	}

	/**
	 * @test
	 */
	public function can_user_delete_group()
	{
		$this->actingAs($user = self::createUser())
			->deleteJson("/groups/non-existent-group", [])
			->assertNotFound();

		$someoneElsesGroup = Group::factory()->create();

		$this->actingAs($user)
			->deleteJson("/groups/{$someoneElsesGroup->id}")
			->assertForbidden()
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')->etc()
			);

		$group = Group::factory()->for($user, 'user')->create();

		$this->actingAs($user)
			->deleteJson("/groups/{$group->id}")
			->assertOk()
			->assertContent('');

		$this->assertDatabaseHas('groups', [
			'id' => $group->id,
			'user_id' => $user->id,
			'deleted_at' => now()
		]);
	}
}

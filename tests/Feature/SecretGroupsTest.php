<?php

namespace Tests\Feature;

use App\Models\SecretGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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
		$this->getJson('/secret-groups')->assertUnauthenticated();
		$this->postJson('/secret-groups')->assertUnauthenticated();
		$this->putJson('/secret-groups/1')->assertUnauthenticated();
		$this->patchJson('/secret-groups/1')->assertUnauthenticated();
		$this->deleteJson('/secret-groups/1')->assertUnauthenticated();
	}

	/**
	 * Can the user be able to get the first-level groups (spaces) that he has created
	 *
	 * @test
	 */
	public function can_user_get_his_spaces(): void
	{
		// Создаем случайную группу, не привязанную к конкретному пользователю
		SecretGroup::factory()->create();

		// ... и убеждаемся, что пользователю, запросившему группы первого уровня (пространства),
		// отдается пустой массив, так как на его имя групп нет
		$this->actingAs($user = self::createUser())
			->getJson('/secret-groups')
			->assertOk()
			->assertJsonIsArray()
			->assertJson(fn (AssertableJson $json) =>
				$json->count(0)
			);

		// Создаем группу от лица конкретного пользователя
		$group = SecretGroup::factory()->for($user, 'user')->create();

		// ... и убеждаемся, что ему пришел массив с 1 элементом с определенным набором свойств.
		// ID группы, пришедший в ответе должен совпадать с ID группы, которая была только что
		// создана выше
		$this->actingAs($user)
			->getJson('/secret-groups')
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
			->getJson("/secret-groups/nonExistentId")
			->assertNotFound();

		// Создаем случайную группу, не привязанную к конкретному пользователю
		$groupWithoutUser = SecretGroup::factory()->create();

		// ... и убеждаемся, что пользователю, запросившему детали не своей группы,
		// возвращается ошибка Forbidden
		$this->actingAs($user)
			->getJson("/secret-groups/{$groupWithoutUser->id}")
			->assertForbidden();

		// Создаем группу от лица конкретного пользователя
		$group = SecretGroup::factory()->for($user, 'user')->create();

		// ... и убеждаемся, что ему пришел объект с определенным набором свойств и
		// ID группы, пришедший в ответе должен совпадать с ID группы, которая была только что
		// создана выше
		$this->actingAs($user)
			->getJson("/secret-groups/{$group->id}")
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
			->postJson('/secret-groups', [])
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->has('details.name')
					->etc()
			);

		$group = $this->actingAs($user)
			->postJson('/secret-groups', ['name' => 'Some first-level group'])
			->assertCreated()
			->assertJsonIsObject()
			->assertJson(fn (AssertableJson $json) =>
				$json->hasAll(
					'id', 'user_id', 'name', 'description', 'alias', 'parent_id', 'created_at',
					'deleted_at'
				)
			)->collect();

		$this->assertDatabaseHas('secret_groups', [
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
		$someoneElsesGroup = SecretGroup::factory()->create();

		$this->actingAs($user = self::createUser())
			->postJson('/secret-groups', [
				'parent_id' => $someoneElsesGroup->id,
				'name' => "Some subgroup with parent \"{$someoneElsesGroup->name}\""
			])
			->assertForbidden()
			->assertJsonIsObject()
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')->etc()
			);

		$parentGroup = SecretGroup::factory()->for($user, 'user')->create();

		$group = $this->actingAs($user)
			->postJson('/secret-groups', [
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

		$this->assertDatabaseHas('secret_groups', [
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
			->patchJson("/secret-groups/non-existent-group", [])
			->assertNotFound();

		$someoneElsesGroup = SecretGroup::factory()->create();

		$this->actingAs($user)
			->patchJson("/secret-groups/{$someoneElsesGroup->id}", [
				'name' => '123'
			])
			->assertForbidden()
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')->etc()
			);

		$group = SecretGroup::factory()->for($user, 'user')->create();

		$this->actingAs($user)
			->patchJson("/secret-groups/{$group->id}", ['name' => '123'])
			->assertOk()
			->assertContent('');

		$this->assertDatabaseHas('secret_groups', [
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
			->deleteJson("/secret-groups/non-existent-group", [])
			->assertNotFound();

		$someoneElsesGroup = SecretGroup::factory()->create();

		$this->actingAs($user)
			->deleteJson("/secret-groups/{$someoneElsesGroup->id}")
			->assertForbidden()
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'forbidden')->etc()
			);

		$group = SecretGroup::factory()->for($user, 'user')->create();

		$this->actingAs($user)
			->deleteJson("/secret-groups/{$group->id}")
			->assertOk()
			->assertContent('');

		$this->assertDatabaseHas('secret_groups', [
			'id' => $group->id,
			'user_id' => $user->id,
			'deleted_at' => now()
		]);
	}
}

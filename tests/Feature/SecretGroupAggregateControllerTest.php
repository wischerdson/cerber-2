<?php

namespace Tests\Feature;

use App\Models\Secret;
use App\Models\SecretField;
use App\Models\SecretGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SecretGroupAggregateControllerTest extends TestCase
{
	use RefreshDatabase;

	public function test_restrict_without_authorization(): void
	{
		$this->getJson('/aggregates/secret-group')->assertUnauthenticated();
	}

	public function test_can_get_group_aggregate(): void
	{
		$this->actingAs($user = self::createUser())
			->getJson('/aggregates/secret-group')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->has('details.group_alias')
					->etc()
			);

		$this->actingAs($user)
			->getJson('/aggregates/secret-group?group_alias=123')
			->assertNotFound();

		$group = SecretGroup::factory()
			->for($user, 'user')
			->has(
				Secret::factory()->has(
					SecretField::factory()->count(2), 'fields'
				)->count(3)
			)
			->has(SecretGroup::factory()->count(2), 'children')
			->for(SecretGroup::factory(), 'parent')
			->create();

		$this->actingAs($user)
			->getJson("/aggregates/secret-group?group_alias={$group->alias}")
			->assertOk()
			->assertJson(fn (AssertableJson $json) => $json
				->has(
					'current_group',
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'description', 'created_at', 'deleted_at')
						->where('id', $group->id)
				)
				->has(
					'children_groups',
					2,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'description', 'created_at', 'deleted_at')
				)
				->has(
					'parent_groups',
					2,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'description', 'created_at', 'deleted_at')
				)
				->has(
					'secrets',
					3,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'alias', 'name', 'notes', 'is_uptodate', 'created_at', 'updated_at', 'deleted_at')
				)
			);
	}
}

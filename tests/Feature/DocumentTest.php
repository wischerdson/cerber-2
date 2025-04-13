<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DocumentTest extends TestCase
{
	use RefreshDatabase;

	public function test_restrict_without_authorization(): void
	{
		$this->postJson('/documents')->assertUnauthenticated();
	}

	public function test_input_validation(): void
	{
		$this->actingAs($user = self::createUser())
			->postJson('/documents')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->where('details.name.0', 'required')
					->where('details.is_group.0', 'required')
					->where('details.fields.0', 'present')
					->etc()
			);

		$this->actingAs($user)
			->postJson('/documents', ['name' => 'Test', 'is_group' => 'hehe', 'fields' => ''])
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->where('details.is_group.0', 'boolean')
					->where('details.fields.0', 'array')
					->etc()
			);

		$response = $this->actingAs($user)
			->postJson('/documents', ['name' => 'Test', 'is_group' => true, 'fields' => []])
			->assertStatus(201)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('name', 'Test')
					->where('is_group', true)
					->hasAll('alias', 'created_at', 'id')
			);
	}
}

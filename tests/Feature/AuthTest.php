<?php

namespace Tests\Feature;

use App\Models\Auth\Grant;
use App\Models\Auth\PasswordGrant;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();

		TestResponse::macro('assertSuccessfulAuthentication', $this->assertSuccessfulAuthentication());
		TestResponse::macro('assertAuthCredentialsError', $this->assertAuthCredentialsError());
	}

	/**
	 * Проверяет валидацию тела запроса при попытке выпустить токен доступа
	 *
	 * @test
	 */
	public function check_request_body_validation(): void
	{
		$this->postJson('/auth/token')
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'validation_failed')
				->has('details', fn (AssertableJson $json) => $json
					->where('grant_type', fn (Collection $array) => $array->containsStrict('required'))
				)
				->etc()
			);

		$this->postJson('/auth/token', ['grant_type' => 'some-wrong-grant-type'])
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'unsupported_grant_type')
				->etc()
			);

		$this->postJson('/auth/token', ['grant_type' => 'password'])
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'validation_failed')
				->has('details', fn (AssertableJson $json) => $json
					->where('login', fn (Collection $array) => $array->containsStrict('required'))
					->where('password', fn (Collection $array) => $array->containsStrict('required'))
				)
				->etc()
			);

		$this->postJson('/auth/token', ['grant_type' => 'refresh_token'])
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'validation_failed')
				->has('details', fn (AssertableJson $json) => $json
					->where('refresh_token', fn (Collection $array) => $array->containsStrict('required'))
				)
				->etc()
			);

		$this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'unknown.login',
			'password' => '123'
		])->assertAuthCredentialsError();

		$this->postJson('/auth/token', [
			'grant_type' => 'refresh_token',
			'refresh_token' => '123'
		])->assertAuthCredentialsError();
	}

	/**
	 * @test
	 */
	public function can_issue_access_token()
	{
		/** @var \Database\Factories\Auth\PasswordGrantFactory */
		$passwordGrantFactory = PasswordGrant::factory()->state([
			'login' => 'test',
			'password' => '123123a'
		]);

		/** @var \Database\Factories\Auth\GrantFactory */
		$grantFactory = Grant::factory()->asActive()->for($passwordGrantFactory, 'extendedGrant');

		/** @var \App\Models\User */
		$user = with(User::factory(), function (UserFactory $factory) use ($grantFactory) {
			return $factory->asNotAdmin()->has($grantFactory)->create();
		});

		$this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'test',
			'password' => '123123A'
		])->assertAuthCredentialsError();

		$response = $this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'test',
			'password' => '123123a'
		])->assertSuccessfulAuthentication();

		$refreshToken = $response->json('refresh_token');

		$response = $this->postJson('/auth/token', [
			'grant_type' => 'refresh_token',
			'refresh_token' => $refreshToken
		])->assertSuccessfulAuthentication();
	}

	private function assertSuccessfulAuthentication(): callable
	{
		return function () {
			/** @var \Illuminate\Testing\TestResponse $this */
			return $this->assertSuccessful()
				->assertJson(fn (AssertableJson $json) => $json
					->where('token_type', 'Bearer')
					->whereType('access_token', 'string')
					->whereType('refresh_token', 'string')
				);
		};
	}

	private function assertAuthCredentialsError(): callable
	{
		return function () {
			/** @var \Illuminate\Testing\TestResponse $this */
			return $this->assertStatus(401)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'auth_credentials_error')
			);
		};
	}
}

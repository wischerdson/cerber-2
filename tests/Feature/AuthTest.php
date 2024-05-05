<?php

namespace Tests\Feature;

use App\Services\Auth\Exceptions\AccessTokenHasExpiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\Helpers;
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
		// Ошибка, если не передать никакие данные аутентификации
		$this->postJson('/auth/token')
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'validation_failed')
				->has('details', fn (AssertableJson $json) => $json
					->where('grant_type', fn (Collection $array) => $array->containsStrict('required'))
				)
				->etc()
			);

		// Ошибка, если передать неподдерживаемый grant-тип
		$this->postJson('/auth/token', ['grant_type' => 'some-wrong-grant-type'])
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'unsupported_grant_type')
				->etc()
			);

		// Ошибка, если не передать данные аутентификации
		$this->postJson('/auth/token', ['grant_type' => 'password'])
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'validation_failed')
				->has('details', fn (AssertableJson $json) => $json
					->where('login', fn (Collection $array) => $array->containsStrict('required'))
					->where('password', fn (Collection $array) => $array->containsStrict('required'))
				)
				->etc()
			);

		// Ошибка, если не передать refresh-токен
		$this->postJson('/auth/token', ['grant_type' => 'refresh_token'])
			->assertStatus(422)->assertJson(fn (AssertableJson $json) => $json
				->where('error_reason', 'validation_failed')
				->has('details', fn (AssertableJson $json) => $json
					->where('refresh_token', fn (Collection $array) => $array->containsStrict('required'))
				)
				->etc()
			);

		// Ошибка, если передать несуществующие данные аутентификации
		$this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'unknown.login',
			'password' => '123'
		])->assertAuthCredentialsError();

		// Ошибка, если передать несуществующий refresh-токен
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
		Helpers::createUser('test', '123123a');

		// Ошибка, если пароль неверен
		$this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'test',
			'password' => '123123A'
		])->assertAuthCredentialsError();

		// Успешный ответ, если все данные аутентификации верны
		$response = $this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'test',
			'password' => '123123a'
		])->assertSuccessfulAuthentication();

		$refreshToken = $response->json('refresh_token');
		$wrongRefreshToken = $this->changeSessionInJwtToken($refreshToken);

		// Ошибка, если данные refresh-токена изменили
		$this->postJson('/auth/token', [
			'grant_type' => 'refresh_token',
			'refresh_token' => $wrongRefreshToken
		])->assertAuthCredentialsError();

		// Ошибка, если refresh-токен истек
		$this->travelTo(now()->addMonth(), fn () =>
			$this->postJson('/auth/token', [
				'grant_type' => 'refresh_token',
				'refresh_token' => $refreshToken
			])->assertAuthCredentialsError()
		);

		// Успешный ответ, если refresh-токен валиден
		$this->postJson('/auth/token', [
			'grant_type' => 'refresh_token',
			'refresh_token' => $refreshToken
		])->assertSuccessfulAuthentication();

		// Ошибка, если refresh-токеном попробовали воспользоваться второй раз
		$this->postJson('/auth/token', [
			'grant_type' => 'refresh_token',
			'refresh_token' => $refreshToken
		])->assertAuthCredentialsError();
	}

	public function test_auth_guard()
	{
		/** @var \Illuminate\Auth\AuthManager */
		$auth = app()->make('auth');
		Helpers::createUser('test', '123123a');

		Route::get('user', fn () => 1)->middleware('auth:api');

		$accessToken = (string) $this->postJson('/auth/token', [
			'grant_type' => 'password',
			'login' => 'test',
			'password' => '123123a'
		])->json('access_token');

		$auth->forgetGuards();

		$this->getJson('/user', ['Authorization' => "Bearer {$accessToken}"])->assertSuccessful();
		$this->assertAuthenticated('api');

		$auth->forgetGuards();

		$this->getJson('/user', ['Authorization' => "Bearer test"])
			->assertStatus(401)->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'unauthenticated')
			);
		$this->assertGuest('api');

		$auth->forgetGuards();

		$this->travelTo(now()->addMinutes(30), function () use ($accessToken, $auth) {
			$this->getJson('/user', ['Authorization' => "Bearer {$accessToken}"])
				->assertStatus(401)
				->assertJson(fn (AssertableJson $json) =>
					$json->where('error_reason', 'access_token_has_expired')
			);
			$this->assertThrows(
				fn () => $auth->guard('api')->user(),
				AccessTokenHasExpiredException::class
			);
		});
	}

	private function changeSessionInJwtToken(string $token): string
	{
		[$headers, $payload, $sign] = explode('.', $token);
		$payload = json_decode(base64_decode($payload), true);
		$payload['ses'] = 123;
		$payload = base64_encode(json_encode($payload));

		return implode('.', [$headers, $payload, $sign]);
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

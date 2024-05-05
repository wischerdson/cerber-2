<?php

namespace Tests;

use App\Models\Auth\Grant;
use App\Models\Auth\PasswordGrant;
use App\Models\User;
use Database\Factories\UserFactory;

class Helpers
{
	public static function createUser(?string $login, ?string $password): User
	{
		/** @var \Database\Factories\Auth\PasswordGrantFactory */
		$passwordGrantFactory = PasswordGrant::factory()->state([
			'login' => $login,
			'password' => $password
		]);

		/** @var \Database\Factories\Auth\GrantFactory */
		$grantFactory = Grant::factory()->asActive()->for($passwordGrantFactory, 'extendedGrant');

		/** @var \App\Models\User */
		return with(User::factory(), function (UserFactory $factory) use ($grantFactory) {
			return $factory->has($grantFactory)->create();
		});
	}
}

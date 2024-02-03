<?php

use App\Models\Auth\Grant;
use App\Models\Auth\PasswordGrant;
use App\Models\User;
use Database\Factories\UserFactory;

test('example', function () {
	/** @var \Database\Factories\Auth\PasswordGrantFactory */
	$passwordGrantFactory = PasswordGrant::factory()->state(['password' => 123123]);

	/** @var \Database\Factories\Auth\GrantFactory */
	$grantFactory = Grant::factory()->for($passwordGrantFactory, 'extendedGrant');

	/** @var \App\Models\User */
	$user = with(User::factory(), function (UserFactory $factory) use ($grantFactory) {
		$factory->asNotAdmin()->has($grantFactory)->create();
	});

	dd(($this));

	$response = $this->post('/auth/token');

	// $response = $this->get('/');

	// $response->assertStatus(200);
});

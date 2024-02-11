<?php

namespace App\Console\Commands;

use App\Facades\Auth;
use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdmin extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:admin';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$login = text(label: 'Admin login', default: 'admin', required: true);

		do {
			$password = password(label: "Password for \"$login\"", required: true);
			$passwordRepeating = password(label: "Please repeat password for \"$login\"", required: true);

			if ($password === $passwordRepeating) {
				break;
			}

			error('Passwords don\'t match');
		} while (true);

		/** @var \App\Services\Auth\GrantTypes\PasswordGrantType */
		$grantType = Auth::grantType('password');
		$grantType->setCredentials($login, $password);

		if ($grantType->hasGrant()) {
			error("User \"$login\" already exists");
			return self::FAILURE;
		}

		$user = new User();
		$user->first_name = fake('en_US')->firstName();
		$user->last_name = fake('en_US')->lastName();
		$user->email = fake('en_US')->safeEmail();
		$user->asAdmin()->save();

		$grantType->createGrantFor($user);

		return self::SUCCESS;
	}
}

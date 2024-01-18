<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Auth\Providers\LoginAuthProvider;
use Illuminate\Console\Command;

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
		$login = $this->ask('Admin login', 'admin');

		do {
			$password = $this->secret("Password for \"$login\"");
			$passwordRepeating = $this->secret("Please repeate password for \"$login\"");

			if ($password === $passwordRepeating) {
				break;
			}

			$this->error('Passwords don\'t match');
		} while (true);

		$authProvider = new LoginAuthProvider($login, $password);

		if ($authProvider->hasEntryPoint()) {
			$this->error("User \"$login\" already exists");
			return self::FAILURE;
		}

		$user = new User();
		$user->asAdmin()->save();
		$authProvider->createEntryPointFor($user);

		return self::SUCCESS;
	}
}

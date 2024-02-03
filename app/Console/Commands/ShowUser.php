<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

class ShowUser extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:show-user';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get user by ID or email';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$idOrEmail = text('User ID or email:');

		$user = User::query()
			->where('id', $idOrEmail)->orWhere('email', $idOrEmail)
			->with('passwordGrant')
			->first();

		if (!$user) {
			error('User not found');

			return self::FAILURE;
		}

		dd($user->toArray());
	}
}

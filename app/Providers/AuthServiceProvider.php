<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Auth\GrantTypes\PasswordGrantType;
use App\Services\Auth\GrantTypes\RefreshTokenGrantType;
use App\Services\Auth\Guard;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		//
	];

	public function register()
	{
		parent::register();

		$this->app->singleton(AuthService::class);
	}

	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		Auth::extend('jwt', function (Application $app) {
			return new Guard($app->make('request'));
		});

		tap($this->app->make(AuthService::class), function (AuthService $service) {
			$service->enableGrantType(new RefreshTokenGrantType());
			$service->enableGrantType(new PasswordGrantType());
		});
	}
}

<?php

namespace App\Providers;

use App\Mixins\Str as StrMixin;
use App\Mixins\FilesystemAdapter as FilesystemAdapterMixin;
use App\Models\Auth\PersonalAccessToken;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		Sanctum::ignoreMigrations();
		Application::getInstance()->useBootstrapPath(base_path('app'));
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerMixins();

		Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
		JsonResource::withoutWrapping();

		Relation::enforceMorphMap([
			'login_auth_provider' => \App\Models\Auth\LoginProviderEntryPoint::class
		]);
	}

	protected function registerMixins()
	{
		Str::mixin(new StrMixin());
		FilesystemAdapter::mixin(new FilesystemAdapterMixin());
	}
}

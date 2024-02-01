<?php

namespace App\Providers;

use App\Mixins\Str as StrMixin;
use App\Mixins\FilesystemAdapter as FilesystemAdapterMixin;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
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

		JsonResource::withoutWrapping();
	}

	protected function registerMixins()
	{
		Str::mixin(new StrMixin());
		FilesystemAdapter::mixin(new FilesystemAdapterMixin());
	}
}

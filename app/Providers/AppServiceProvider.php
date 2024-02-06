<?php

namespace App\Providers;

use App\Mixins\Str as StrMixin;
use App\Mixins\FilesystemAdapter as FilesystemAdapterMixin;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

		env('DB_LOG_SQL_QUERIES', false) && $this->enableDbQueriesLogging();
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

		Relation::enforceMorphMap([
			\App\Services\Auth\GrantTypes\PasswordGrantType::getIdentifier() . '_grant' => \App\Models\Auth\PasswordGrant::class,
			\App\Services\Auth\GrantTypes\RefreshTokenGrantType::getIdentifier() . '_grant' => \App\Models\Auth\RefreshTokenGrant::class
		]);
	}

	protected function registerMixins()
	{
		Str::mixin(new StrMixin());
		FilesystemAdapter::mixin(new FilesystemAdapterMixin());
	}

	protected function enableDbQueriesLogging()
	{
		DB::listen(function (QueryExecuted $query) {
			$sql = DB::connection()
				->getQueryGrammar()
				->substituteBindingsIntoRawSql($query->sql, $query->bindings);

			Log::channel('sql')->debug("({$query->time}) " . $sql);
		});
	}
}

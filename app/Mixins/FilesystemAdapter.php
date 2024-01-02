<?php

namespace App\Mixins;

use App\Services\Storage\StorageService;
use Illuminate\Container\Container;

class FilesystemAdapter
{
	public function upload(): callable
	{
		return $this->callServiceMethod(__FUNCTION__);
	}

	private function callServiceMethod(string $method): callable
	{
		return function (...$args) use ($method) {
			/** @var \Illuminate\Filesystem\FilesystemAdapter $this */

			/** @var \App\Services\Storage\StorageService $service */
			$service = Container::getInstance()->make(StorageService::class);
			$service->setAdapter($this);

			return $service->$method(...$args);
		};
	}
}

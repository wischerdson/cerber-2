<?php

namespace App\Services\Storage;

use App\Models\StorageDir;
use Exception;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class Dir
{
	public const MAX_FILES_IN_DIR = 8000;

	public const MAX_DIRS_IN_DIR = 2000;

	private FilesystemAdapter $adapter;

	private string $disk;

	public function setAdapter(FilesystemAdapter $adapter)
	{
		$this->adapter = $adapter;

		if (!$this->disk = Arr::get($this->adapter->getConfig(), 'name')) {
			throw new RuntimeException('Disk name is not defined. Please specify "name" key in your disk configuration.');
		}

		!Application::getInstance()->isProduction() && $this->checkDiskForDuplicates();
	}

	public function getModel(?string $category = null): StorageDir
	{
		$key = $this->disk . ($category ? ".{$category}" : '');

		return Cache::remember(
			"category_dir:{$key}",
			now()->addMinute(),
			fn () => StorageDir::query()
				->where('category', $category)
				->where('disk', $this->disk)
				->where('files_count', '<', self::MAX_FILES_IN_DIR)
				->oldest()
				->firstOr(fn () => $this->createNewDir($category))
		);
	}

	private function createNewDir(?string $category = null): StorageDir
	{
		$parentDir = $this->findDir($category);

		do {
			$dirName = Str::randHex();
			$path = Str::finish($parentDir->path, '/') . $dirName;
		} while ($this->adapter->directoryExists($path));

		$this->adapter->makeDirectory($path);

		$dir = new StorageDir();
		$dir->name = $dirName;
		$dir->category = $category;
		$dir->path = $path;
		$dir->disk = $this->disk;
		$parentDir->children()->save($dir);

		$parentDir->increment('dirs_count');

		return $dir;
	}

	private function findDir(?string $category = null): StorageDir
	{
		return StorageDir::query()
			->where('disk', $this->disk)
			->where('category', $category)
			->where('dirs_count', '<', self::MAX_DIRS_IN_DIR)
			->oldest()
			->firstOr(fn () => $this->getRootDir($category));
	}

	private function getRootDir(?string $category = null)
	{
		$rootDir = StorageDir::query()
			->where('disk', $this->disk)
			->where('category', $category)
			->whereNull('parent_id')
			->firstOrNew();

		$rootDir->disk = $this->disk;
		$rootDir->name = $rootDir->category = $rootDir->path = $category;
		$rootDir->save();

		return $rootDir;
	}

	/**
	 * @throws \Exception
	 */
	private function checkDiskForDuplicates()
	{
		$disks = config('filesystems.disks');

		if (!$path = Arr::get($disks, "{$this->disk}.root")) {
			return;
		}

		$path = rtrim($path, '/');

		unset($disks[$this->disk]);

		foreach ($disks as $key => $disk) {
			if ($path === rtrim(Arr::get($disk, 'root'), '/')) {
				throw new Exception("Selected disk ({$this->disk}) has same root path as \"{$key}\" disk.");
			}
		}
	}
}

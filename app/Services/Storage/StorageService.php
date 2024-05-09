<?php

namespace App\Services\Storage;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class StorageService
{
	private FilesystemAdapter $adapter;

	private Dir $dir;

	public function __construct()
	{
		$this->dir = new Dir();
	}

	public function setAdapter(FilesystemAdapter $adapter)
	{
		$this->adapter = $adapter;
		$this->dir->setAdapter($adapter);
	}

	/**
	 * @return array{0: string, 1: \App\Models\StorageDir}
	 */
	public function upload(UploadedFile $file, ?string $category = null): array
	{
		$dir = $this->dir->getModel($category);

		do {
			if ($extension = $file->guessExtension()) {
				$extension = '.'.$extension;
			}

			$filename = time() . '-' . Str::randHex() . $extension;
			$filePath = Str::finish($dir->path, '/') . $filename . $extension;
		} while (file_exists($filePath));

		if (!$file = $this->adapter->putFileAs($dir->path, $file, $filename)) {
			throw new RuntimeException('Failed to save file');
		}

		$dir->increment('files_count');

		return [$file, $dir];
	}
}

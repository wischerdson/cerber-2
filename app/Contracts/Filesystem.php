<?php

namespace App\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem as BaseFilesystemContract;
use Illuminate\Http\UploadedFile;

interface Filesystem extends BaseFilesystemContract
{
	/**
	 * @return array{0: string, 1: \App\Models\StorageDir}
	 */
	public function upload(UploadedFile $file, ?string $category = null): array;

	/**
	 * @see \Illuminate\Filesystem\FilesystemAdapter::path
	 */
	public function path(string $path): string;
}

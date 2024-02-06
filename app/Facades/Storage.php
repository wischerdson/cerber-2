<?php

namespace App\Facades;

use Illuminate\Support\Facades\Storage as BaseStorageFacade;

/**
 * @method static \App\Contracts\Filesystem drive(string|null $name = null)
 * @method static \App\Contracts\Filesystem disk(string|null $name = null)
 * @method static \App\Contracts\Filesystem build(string|array $config)
 * @method static \App\Contracts\Filesystem createLocalDriver(array $config)
 * @method static \App\Contracts\Filesystem createFtpDriver(array $config)
 * @method static \App\Contracts\Filesystem createSftpDriver(array $config)
 * @method static \App\Contracts\Filesystem createScopedDriver(array $config)
 * @method static \App\Models\File upload(\Illuminate\Http\UploadedFile $file, string|null $category)
 *
 * @see \App\Services\Storage\StorageService
 */
class Storage extends BaseStorageFacade
{
}

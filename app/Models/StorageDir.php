<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $category
 * @property string|null $path
 * @property string $disk
 * @property int $files_count
 * @property int $dirs_count
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class StorageDir extends Model
{
	use HasFactory;

	protected $table = 'storage_dirs';

	protected $fillable = ['name', 'category', 'path'];

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function files(): HasMany
	{
		return $this->hasMany(File::class, 'storage_dir_id');
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $fileable_type
 * @property int $fileable_id
 * @property string $entity_type
 * @property int $entity_id
 * @property int $parent_id
 * @property int $storage_dir_id
 * @property int $user_id
 * @property string $name
 * @property string $path
 * @property int $size
 * @property string $mime
 * @property string $md5_hash
 * @property string $sha1_hash
 * @property string $created_at
 */
class File extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $table = 'files';

	protected $fillable = ['name', 'size', 'mime', 'md5_hash', 'sha1_hash'];

	protected $hidden = ['storage_dir_id', 'user_id', 'entity_id', 'fileable_type', 'fileable_id'];

	protected $casts = [
		'created_at' => 'timestamp',
	];

	public function entity(): MorphTo
	{
		return $this->morphTo();
	}

	public function fileable(): MorphTo
	{
		return $this->morphTo();
	}

	public function storageDir(): BelongsTo
	{
		return $this->belongsTo(StorageDir::class, 'storage_dir_id');
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property bool $is_group
 * @property string $alias
 * @property string $name
 * @property string|null $notes
 * @property bool $is_effective
 * @property string $created_at
 * @property string|null $deleted_at
 */
class Document extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected static $unguarded = true;

	protected $table = 'documents';

	protected $casts = [
		'created_at' => 'timestamp',
		'deleted_at' => 'timestamp',
		'is_group' => 'boolean',
		'is_effective' => 'boolean'
	];

	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function descendants(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function fields(): HasMany
	{
		return $this->hasMany(DocumentField::class, 'document_id');
	}

	protected static function booted(): void
	{
		static::creating(function (self $document) {
			$i = 5;

			do {
				$alias = mb_strtolower(Str::random($i++));
			} while (self::query()->where('alias', $alias)->exists());

			$document->alias = $alias;
		});
	}
}

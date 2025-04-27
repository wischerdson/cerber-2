<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $creator_id
 * @property int|null $owner_id
 * @property string $type
 * @property string $alias
 * @property string $name
 * @property string|null $notes
 * @property bool $is_effective
 * @property string $created_at
 * @property string|null $deleted_at
 */
class Node extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	const TYPE_GROUP = 'group';

	const TYPE_DOCUMENT = 'document';

	const TYPE_LINK = 'link';

	protected $fillable = ['parent_id', 'type', 'name', 'notes', 'is_effective'];

	protected $table = 'nodes';

	protected $hidden = ['parent_id', 'creator_id', 'owner_id'];

	protected $casts = [
		'created_at' => 'timestamp',
		'deleted_at' => 'timestamp',
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

	public function document_fields(): HasMany
	{
		return $this->hasMany(DocumentField::class, 'document_id');
	}

	#[Scope]
	protected function whereTypeIsGroup(Builder $query): void
	{
		$query->where('type', self::TYPE_GROUP);
	}

	protected static function booted(): void
	{
		static::creating(function (self $node) {
			$i = 5;

			do {
				$alias = mb_strtolower(Str::random($i++));
			} while (self::query()->where('alias', $alias)->exists());

			$node->alias = $alias;
		});
	}
}

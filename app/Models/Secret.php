<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property string $notes
 * @property boolean $is_uptodate
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class Secret extends Model
{
	use HasFactory;

	protected static $unguarded = true;

	protected $table = 'secrets';

	protected $casts = [
		'created_at' => 'timestamp',
		'updated_at' => 'timestamp',
		'deleted_at' => 'timestamp',
		'is_uptodate' => 'boolean'
	];

	protected $hidden = ['pivot'];

	public function fields(): HasMany
	{
		return $this->hasMany(SecretField::class, 'secret_id');
	}

	public function group(): BelongsToMany
	{
		return $this->belongsToMany(SecretGroup::class, 'secrets_in_groups', 'secret_id', 'group_id');
	}

	protected static function booted(): void
	{
		static::creating(function (self $group) {
			$i = 5;

			do {
				$alias = mb_strtolower(Str::random($i++));
			} while (self::query()->where('alias', $alias)->exists());

			$group->alias = $alias;
		});
	}
}

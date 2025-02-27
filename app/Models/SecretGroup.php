<?php

namespace App\Models;

use App\Facades\Auth;
use Database\Factories\SecretGroupFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property int $parent_id
 * @property string $created_at
 * @property ?string $deleted_at
 *
 * @method \Illuminate\Database\Eloquent\Builder forCurrentUser
 */
class SecretGroup extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected static $factory = SecretGroupFactory::class;

	protected $fillable = ['name', 'description'];

	protected $table = 'secret_groups';

	protected $casts = [
		'created_at' => 'timestamp',
		'deleted_at' => 'timestamp'
	];

	protected $hidden = ['user_id', 'parent_id'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function secrets(): BelongsToMany
	{
		return $this->belongsToMany(Secret::class, 'secrets_in_groups', 'group_id', 'secret_id');
	}

	public function scopeForCurrentUser(Builder $query): void
	{
		if (!$user = Auth::user()) {
			throw new Exception('User is not defined');
		}

		$query->where('user_id', $user->id);
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

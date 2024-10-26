<?php

namespace App\Models;

use App\Facades\Auth;
use Database\Factories\GroupFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
class Group extends Model
{
	use HasFactory, HasSlug;

	const UPDATED_AT = null;

	protected static $factory = GroupFactory::class;

	protected $fillable = ['name', 'description'];

	protected $table = 'groups';

	protected $casts = [
		'created_at' => 'timestamp',
		'deleted_at' => 'timestamp'
	];

	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()
			->generateSlugsFrom('name')
			->saveSlugsTo('alias');
	}

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

	public function scopeForCurrentUser(Builder $query): void
	{
		if (!$user = Auth::user()) {
			throw new Exception('User is not defined');
		}

		$query->where('user_id', $user->id);
	}
}

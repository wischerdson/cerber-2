<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 */
class Secret extends Model
{
	use HasFactory;

	protected static $unguarded = true;

	protected $table = 'secrets';

	public function fields(): HasMany
	{
		return $this->hasMany(SecretField::class, 'secret_id');
	}
}

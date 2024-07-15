<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $secret_id
 * @property string $name
 * @property string $short_description
 * @property string $value
 * @property bool $multiline
 * @property bool $secure
 * @property int $sort
 */
class SecretField extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected static $unguarded = true;

	protected $table = 'secret_fields';

	public function secret(): BelongsTo
	{
		return $this->belongsTo(Secret::class, 'secret_id');
	}

	protected function casts(): array
    {
        return [
            'multiline' => 'boolean',
            'secure' => 'boolean'
        ];
    }
}

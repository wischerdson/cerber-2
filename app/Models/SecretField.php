<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

/**
 * @property int $id
 * @property int $secret_id
 * @property string $label
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

	protected $casts = [
		'multiline' => 'boolean',
		'secure' => 'boolean'
	];

	public function secret(): BelongsTo
	{
		return $this->belongsTo(Secret::class, 'secret_id');
	}

	public function value(): Attribute
	{
		return Attribute::make(
			get: fn (string $value) => $this->secure ? Crypt::decryptString($value) : $value,
			set: fn (string $value) => $this->secure ? Crypt::encryptString($value) : $value
		);
	}
}

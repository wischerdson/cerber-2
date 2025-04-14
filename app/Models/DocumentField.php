<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

/**
 * @property int $id
 * @property int $document_id
 * @property string $label
 * @property string|null $short_description
 * @property string $value
 * @property bool $is_multiline
 * @property bool $is_secure
 * @property int $sort
 */
class DocumentField extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected static $unguarded = true;

	protected $table = 'document_fields';

	protected $casts = [
		'is_multiline' => 'boolean',
		'is_secure' => 'boolean'
	];

	public function value(): Attribute
	{
		return Attribute::make(
			get: function (string $value) {
				try {
					return $this->secure ? Crypt::decryptString($value) : $value;
				} catch (DecryptException $e) {
					return $value;
				}
			},
			set: fn (string $value) => $this->secure ? Crypt::encryptString($value) : $value
		);
	}

	public function document(): BelongsTo
	{
		return $this->belongsTo(Node::class, 'document_id');
	}
}

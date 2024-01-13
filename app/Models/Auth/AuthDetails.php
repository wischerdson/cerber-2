<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $personal_access_token_id
 * @property int $entry_point_id
 * @property string $user_agent
 * @property string $ip
 * @property string $ip_country_code
 * @property string $ip_region
 * @property string $ip_city
 */
class AuthDetails extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $table = 'auth_details';

	protected static $unguarded = true;

	public function personalAccessToken(): BelongsTo
	{
		return $this->belongsTo(PersonalAccessToken::class, 'personal_access_token_id');
	}

	public function entryPoint(): BelongsTo
	{
		return $this->belongsTo(EntryPoint::class, 'entry_point_id');
	}
}

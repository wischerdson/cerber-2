<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property \App\Models\User $user
 * @property string $provider
 * @property int $provider_entry_point_id
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class EntryPoint extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $table = 'auth_entry_points';

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function providerEntryPoint(): MorphTo
	{
		return $this->morphTo('provider', 'provider', 'provider_entry_point_id');
	}

	public function authDetails(): HasMany
	{
		return $this->hasMany(AuthDetails::class, 'entry_point_id');
	}
}

<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

abstract class AbstractExtendedGrant extends Model
{
	use HasFactory;

	public function baseGrant(): MorphOne
	{
		return $this->morphOne(Grant::class, 'grant', 'grant_type', 'grant_id');
	}
}

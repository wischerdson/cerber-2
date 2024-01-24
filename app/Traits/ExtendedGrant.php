<?php

namespace App\Traits;

use App\Models\Auth\Grant;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait ExtendedGrant
{
	public function baseGrant(): MorphOne
	{
		return $this->morphOne(Grant::class, 'grant', 'grant_type', 'grant_id');
	}
}

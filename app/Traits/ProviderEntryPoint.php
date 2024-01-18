<?php

namespace App\Traits;

use App\Models\Auth\EntryPoint;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait ProviderEntryPoint
{
	public function baseEntryPoint(): MorphOne
	{
		return $this->morphOne(EntryPoint::class, 'provider', 'provider', 'provider_entry_point_id');
	}
}

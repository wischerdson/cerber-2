<?php

namespace App\Contracts\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface ProviderEntryPoint
{
	public function baseEntryPoint(): MorphOne;
}

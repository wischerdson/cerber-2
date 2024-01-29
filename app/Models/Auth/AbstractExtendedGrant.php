<?php

namespace App\Models\Auth;

use App\Traits\ExtendedGrant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractExtendedGrant extends Model
{
	use HasFactory, ExtendedGrant;
}

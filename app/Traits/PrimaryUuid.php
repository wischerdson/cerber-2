<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait PrimaryUuid
{
	public function getIncrementing()
	{
		return false;
	}

	public function getKeyType()
	{
		return 'string';
	}

	public function getKeyName()
	{
		return 'uuid';
	}

	protected static function bootPrimaryUuid()
	{
		static::creating(function ($model) {
			!$model->getKey() && $model->{$model->getKeyName()} = Str::uuid();
		});
	}
}

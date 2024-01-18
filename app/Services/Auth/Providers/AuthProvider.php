<?php

namespace App\Services\Auth\Providers;

use App\Models\Auth\EntryPoint;
use App\Models\User;

abstract class AuthProvider
{
	abstract public function getEntryPointModelClass(): string;

	abstract public function findEntryPoint(bool $checkCredentials): ?EntryPoint;

	abstract public function hasEntryPoint(): bool;

	abstract public function createEntryPointFor(User $user): EntryPoint;
}

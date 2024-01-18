<?php

namespace App\Services\Auth\Providers;

use App\Exceptions\InvalidCredentialsException;
use App\Models\Auth\EntryPoint;
use App\Models\Auth\LoginProviderEntryPoint;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginAuthProvider extends AuthProvider
{
	private string $login;

	private string $password;

	public function __construct(string $login, string $password)
	{
		$this->setCredentials($login, $password);
	}

	public function setCredentials(string $login, string $password): void
	{
		$this->login = $login;
		$this->password = $password;
	}

	public function getEntryPointModelClass(): string
	{
		return LoginProviderEntryPoint::class;
	}

	/**
	 * @throws \App\Exceptions\InvalidCredentialsException
	 */
	public function findEntryPoint(bool $checkCredentials = true): ?EntryPoint
	{
		$class = $this->getEntryPointModelClass();

		$providerEntryPoint = $class::query()
			->where('login', $this->login)
			->first();

		if ($checkCredentials) {
			if (!$providerEntryPoint) {
				throw new InvalidCredentialsException();
			}

			if (!Hash::check($this->password, $providerEntryPoint->password_hash)) {
				throw new InvalidCredentialsException();
			}
		}

		if (!$providerEntryPoint) {
			return null;
		}

		/** @var \App\Models\Auth\EntryPoint $entryPoint */
		$entryPoint = $providerEntryPoint->baseEntryPoint;

		return $entryPoint->setRelation('providerEntryPoint', $providerEntryPoint);;
	}

	public function hasEntryPoint(): bool
	{
		$class = $this->getEntryPointModelClass();

		return $class::query()->where('login', $this->login)->exists();
	}

	public function createEntryPointFor(User $user): EntryPoint
	{
		$class = $this->getEntryPointModelClass();

		$providerEntryPoint = new $class();
		$providerEntryPoint->login = $this->login;
		$providerEntryPoint->password = $this->password;

		$entryPoint = new EntryPoint();
		$entryPoint->user_id = $user->getKey();
		$entryPoint->providerEntryPoint()->save($providerEntryPoint);

		return $entryPoint->setRelation('providerEntryPoint', $providerEntryPoint);
	}
}

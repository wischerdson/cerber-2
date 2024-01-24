<?php

namespace App\Services\Auth\GrantTypes;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\TokenFactory;
use App\Services\Auth\TokensPair;
use Illuminate\Http\Request;

abstract class AbstractGrantType
{
	// abstract public function getProviderEntryPointModelClass(): string;

	// abstract protected function fillEntryPoint(ProviderEntryPoint $providerEntryPoint): ProviderEntryPoint;

	// abstract protected function filterProviderEntryPoint(Builder $query): void;

	// abstract protected function checkCredentials(ProviderEntryPoint $providerEntryPoint): bool;

	/**
	 * Return the grant identifier that can be used in matching up requests.
	 */
	abstract public static function getIdentifier(): string;

	abstract public function respondToAccessTokenRequest(Request $request): TokensPair;

	// public function hasEntryPoint(): bool
	// {
	// 	/** @var \Illuminate\Database\Eloquent\Builder $query */
	// 	$query = $this->getProviderEntryPointModelClass()::query();
	// 	$this->filterProviderEntryPoint($query);

	// 	return $query->exists();
	// }

	public function canRespondToAccessTokenRequest(Request $request)
	{
		$request->validate([
			'grant_type' => 'required',
		]);

		return $request->grant_type === $this->getIdentifier();
	}

	protected function makeTokensPair(AuthSession $session): TokensPair
	{
		$accessTokenFactory = new TokenFactory();
		$accessTokenFactory->setAuthSession($session);
		$accessTokenFactory->expiresAt(now()->addMinutes(30));

		$refreshTokenFactory = new TokenFactory();
		$refreshTokenFactory->setAuthSession($session);
		$refreshTokenFactory->expiresAt(now()->addMonth());

		return TokensPair::make($accessTokenFactory, $refreshTokenFactory);
	}

	/**
	 * @throws \App\Exceptions\InvalidCredentialsException
	 */
	// public function findEntryPoint(bool $checkCredentials = true): ?EntryPoint
	// {
	// 	$class = $this->getProviderEntryPointModelClass();

	// 	$query = $class::query();
	// 	$this->filterProviderEntryPoint($query);

	// 	$providerEntryPoint = $query->first();

	// 	if ($checkCredentials) {
	// 		if (!$providerEntryPoint) {
	// 			throw new InvalidCredentialsException();
	// 		}

	// 		if (!$this->checkCredentials($providerEntryPoint)) {
	// 			throw new InvalidCredentialsException();
	// 		}
	// 	}

	// 	if (!$providerEntryPoint) {
	// 		return null;
	// 	}

	// 	/** @var \App\Models\Auth\EntryPoint $entryPoint */
	// 	$entryPoint = $providerEntryPoint->baseEntryPoint;

	// 	return $entryPoint->setRelation('providerEntryPoint', $providerEntryPoint);;
	// }

	// public function createGrantFor(User $user): Grant
	// {
	// 	$class = $this->getProviderEntryPointModelClass();

	// 	$providerEntryPoint = $this->fillEntryPoint(new $class());

	// 	$entryPoint = new EntryPoint();
	// 	$entryPoint->setAttribute(
	// 		$entryPoint->user()->getForeignKeyName(),
	// 		$user->getKey()
	// 	);
	// 	$entryPoint->providerEntryPoint()->associate($providerEntryPoint);

	// 	return $entryPoint->setRelation('providerEntryPoint', $providerEntryPoint);
	// }
}

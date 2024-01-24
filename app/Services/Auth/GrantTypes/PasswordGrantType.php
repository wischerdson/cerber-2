<?php

namespace App\Services\Auth\GrantTypes;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\TokensPair;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordGrantType extends AbstractGrantType
{
	private string $login;

	private string $password;

	public static function getIdentifier(): string
	{
		return 'password';
	}

	public function canRespondToAccessTokenRequest(Request $request)
	{
		return (
			parent::canRespondToAccessTokenRequest($request) &&
			$request->validate([
				'login' => 'required',
				'password' => 'required',
			])
		);
	}

	public function respondToAccessTokenRequest(Request $request): TokensPair
	{
		$session = new AuthSession();

		return $this->makeTokensPair($session);
	}

	public function createNewGrant()
	{

	}

	// public function setCredentials(string $login, string $password): void
	// {
	// 	$this->login = $login;
	// 	$this->password = $password;
	// }

	// public function getProviderEntryPointModelClass(): string
	// {
	// 	return LoginProviderEntryPoint::class;
	// }

	// protected function checkCredentials(ProviderEntryPoint $providerEntryPoint): bool
	// {
	// 	return Hash::check($this->password, $providerEntryPoint->password_hash);
	// }

	// protected function filterProviderEntryPoint(Builder $query): void
	// {
	// 	$query->where('login', $this->login);
	// }

	// protected function fillEntryPoint(ProviderEntryPoint $providerEntryPoint): ProviderEntryPoint
	// {
	// 	$providerEntryPoint->login = $this->login;
	// 	$providerEntryPoint->password = $this->password;

	// 	return $providerEntryPoint;
	// }


}

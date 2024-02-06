<?php

namespace App\Services\Auth\GrantTypes;

use App\Models\Auth\PasswordGrant;
use App\Services\Auth\Exceptions\AuthCredentialsErrorException;
use App\Services\Auth\TokensPair;
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

	public function setCredentials(string $login, string $password)
	{
		$this->login = $login;
		$this->password = $password;
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

	/**
	 * @throws \App\Services\Auth\Exceptions\AuthCredentialsErrorException
	 */
	public function respondToAccessTokenRequest(Request $request): TokensPair
	{
		$this->setCredentials($request->login, $request->password);

		if (
			(!$grant = $this->findGrant()) ||
			(!$baseGrant = $grant->baseGrant) ||
			(!$baseGrant->is_active)
		) {
			throw new AuthCredentialsErrorException();
		}

		return $this->makeTokensPair(
			$this->createSession($baseGrant, $request),
			$this->createRefreshTokenGrant($baseGrant->user_id)
		);
	}

	public function hasGrant(): bool
	{
		return PasswordGrant::query()->where('login', $this->login)->exists();
	}

	public function findGrant(): ?PasswordGrant
	{
		/** @var \App\Models\Auth\PasswordGrant $grant */
		$grant = PasswordGrant::query()
			->where('login', $this->login)
			->first();

		if ($grant && Hash::check($this->password, $grant->password_hash)) {
			return $grant;
		}

		return null;
	}

	protected function createGrant(): PasswordGrant
	{
		$grant = new PasswordGrant();
		$grant->login = $this->login;
		$grant->password = $this->password;
		$grant->save();

		return $grant;
	}
}

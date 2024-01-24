<?php

namespace App\Services\Auth\GrantTypes;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\TokensPair;
use Illuminate\Http\Request;

class RefreshTokenGrantType extends AbstractGrantType
{
	public static function getIdentifier(): string
	{
		return 'refresh_token';
	}

	public function respondToAccessTokenRequest(Request $request): TokensPair
	{
		$session = new AuthSession();

		return $this->makeTokensPair($session);
	}
}

<?php

namespace App\Services\Auth;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\Exceptions\UnsupportedGrantTypeException;
use App\Services\Auth\GrantTypes\AbstractGrantType;
use App\Services\Auth\ResponseTypes\BearerTokenResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
	/** @var \App\Services\Auth\GrantTypes\AbstractGrantType */
	protected array $enabledGrantTypes = [];

	private Request $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function enableGrantType(AbstractGrantType $grant)
	{
		$this->enabledGrantTypes[$grant::getIdentifier()] = $grant;
	}

	public function currentSession(): AuthSession
	{
		return with(Auth::guard('api'), function (Guard $guard) {
			return $guard->currentSession();
		});
	}

	/**
	 * @throws \App\Services\Auth\Exceptions\UnsupportedGrantTypeException
	 */
	public function respondToAccessTokenRequest(): Response
	{
		/** @var \App\Services\Auth\GrantTypes\AbstractGrantType $grantType */
		foreach ($this->enabledGrantTypes as $grantType) {
			if (!$grantType->canRespondToAccessTokenRequest($this->request)) {
				continue;
			}

			$tokensPair = $grantType->respondToAccessTokenRequest($this->request);

			return $this->makeHttpResponse($tokensPair);
		}

		throw new UnsupportedGrantTypeException();
	}

	protected function makeHttpResponse(TokensPair $pair): Response
	{
		$responseType = new BearerTokenResponse();
		$responseType->setTokensPair($pair);

		return $responseType->makeHttpResponse();
	}



	// private function detailAuthentication(PersonalAccessToken $token, EntryPoint $entryPoint)
	// {
	// 	$authDetails = new AuthDetails();
	// 	$authDetails->personal_access_token_id = $token->id;
	// 	$authDetails->user_agent = $this->request->header('User-Agent');
	// 	$authDetails->ip = $this->request->ip();
	// 	$entryPoint->authDetails()->save($authDetails);
	// }
}

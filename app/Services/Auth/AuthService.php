<?php

namespace App\Services\Auth;

use App\Models\Auth\Session as AuthSession;
use App\Services\Auth\Exceptions\UnsupportedGrantTypeException;
use App\Services\Auth\GrantTypes\AbstractGrantType;
use App\Services\Auth\TokenFactory;
use App\Services\Auth\TokensPair;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator as JwtValidator;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
	/** @var \App\Services\Auth\GrantTypes\AbstractGrantType[] */
	protected array $enabledGrantTypes = [];

	private Application $app;

	public static function parseToken(string $rawToken, ?callable $beforeValidate = null): ?UnencryptedToken
	{
		$parser = new Parser(new JoseEncoder());

		try {
			$token = $parser->parse($rawToken);
		} catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
			return null;
		}

		$beforeValidate && $beforeValidate($token);

		$validator = new JwtValidator();

		if ($token->isExpired(now())) {
			return null;
		}

		$signedWith = new SignedWith(new Sha256(), TokenFactory::getSigningKey());

		if (!$validator->validate($token, $signedWith)) {
			return null;
		}

		return $token;
	}

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * @throws \App\Services\Auth\Exceptions\UnsupportedGrantTypeException
	 */
	public function grantType(string $identifier): ?AbstractGrantType
	{
		return ($grant = @$this->enabledGrantTypes[$identifier]) ? $grant : throw new UnsupportedGrantTypeException();
	}

	public function enableGrantType(AbstractGrantType $grant)
	{
		$this->enabledGrantTypes[$grant::getIdentifier()] = $grant;
	}

	public function apiGuard(): Guard
	{
		return Auth::guard('api');
	}

	public function currentSession(): AuthSession
	{
		return $this->apiGuard()->currentSession();
	}

	public function user()
	{
		return $this->apiGuard()->user();
	}

	/**
	 * @throws \App\Services\Auth\Exceptions\UnsupportedGrantTypeException
	 */
	public function respondToAccessTokenRequest(): Response
	{
		/** @var \Illuminate\Http\Request */
		$request = $this->app->make('request');

		/** @var \App\Services\Auth\GrantTypes\AbstractGrantType $grantType */
		foreach ($this->enabledGrantTypes as $grantType) {
			if (!$grantType->canRespondToAccessTokenRequest($request)) {
				continue;
			}

			$tokensPair = $grantType->respondToAccessTokenRequest($request);

			return $this->makeHttpResponse($tokensPair);
		}

		throw new UnsupportedGrantTypeException();
	}

	public function findSessionByToken(UnencryptedToken $token): ?AuthSession
	{
		/** @var \Illuminate\Http\Request */
		$request = $this->app->make('request');
		$sessionId = $token->claims()->get(TokenFactory::SESSION_ID_CLAIM);

		return AuthSession::query()
			->where('id', $sessionId)
			->where('revoked', false)
			->where('user_agent', $request->header('User-Agent'))
			->first();
	}

	protected function makeHttpResponse(TokensPair $pair): Response
	{
		$responseParams = [
			'token_type' => 'Bearer',
			'access_token' => $pair->issueAccessToken(),
			'refresh_token' => $pair->issueRefreshToken(),
		];

		return response()->json($responseParams, 201, [
			'pragma' => 'no-cache',
			'cache-control' => 'no-store'
		]);
	}
}

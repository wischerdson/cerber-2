<?php

namespace App\Http\Controllers;

use App\Facades\Auth;

class AuthController extends Controller
{
	public function token()
	{
		return Auth::respondToAccessTokenRequest();
	}

	public function revokeSession()
	{
		Auth::currentSession()->revoke();
	}

	public function user()
	{
		return [
			'user' => Auth::user(),
			'session' => Auth::currentSession()
		];
	}
}

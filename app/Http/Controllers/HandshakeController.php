<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenSslPublicKeyInvalidException;
use App\Models\Handshake;
use App\Rules\OpenSslRsaPublicKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HandshakeController extends Controller
{
	public function create(Request $request)
	{
		$clientPublicKey = $request->getContent();

		if (Validator::make([$clientPublicKey], ['required', new OpenSslRsaPublicKey()])->fails()) {
			throw new OpenSslPublicKeyInvalidException();
		}

		$handshake = new Handshake();
		$handshake->client_public_key = $request->getContent() ?: null;
		$handshake->save();

		return [
			'id' => $handshake->uuid,
			'server_public_key' => $handshake->server_public_key,
		];
	}

	public function test(Request $request) {
		$request->dd();
	}
}

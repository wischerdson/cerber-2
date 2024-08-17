<?php

namespace Database\Factories;

use App\Models\Handshake;
use App\Services\Encryption\RsaEncrypter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Handshake>
 */
class HandshakeFactory extends Factory
{
	/** @var string */
	protected $model = Handshake::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		[$publicKey] = RsaEncrypter::createKeyPair();

		return [
			'client_public_key' => $publicKey
		];
	}
}

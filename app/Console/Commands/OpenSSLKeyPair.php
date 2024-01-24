<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenSSLAsymmetricKey;

use function Laravel\Prompts\confirm;

class OpenSSLKeyPair extends Command
{
	protected $signature = 'openssl:create-key-pair {--f|force}';

	protected $description = 'The public/private key pair is used to sign and verify JWTs transmitted';

	private string $privateKeyFile;

	private string $publicKeyFile;

	public function __construct()
	{
		$this->privateKeyFile = config('auth.openssl_private_key_file');
		$this->publicKeyFile = config('auth.openssl_public_key_file');

		parent::__construct();
	}

	public function handle()
	{
		if (
			file_exists($this->publicKeyFile) &&
			file_exists($this->privateKeyFile) &&
			!$this->option('force')
		) {
			$confirmed = confirm(
				label: 'OpenSSL key pair already exists. Do you want to overwrite it?',
				default: false
			);

			if (!$confirmed) {
				return self::SUCCESS;
			}
		}

		$privateKey = $this->getPrivateKey();

		$publicKeyDetails = openssl_pkey_get_details($privateKey);
		$publicKey = $publicKeyDetails['key'];

		openssl_pkey_export($privateKey, $privateKeyExported);

		file_put_contents($this->privateKeyFile, $privateKeyExported);
		file_put_contents($this->publicKeyFile, $publicKey);

		return self::SUCCESS;
	}

	private function getPrivateKey(): OpenSSLAsymmetricKey
	{
		$shouldCreateNewPrivateKey = true;

		if (
			file_exists($this->privateKeyFile) &&
			!file_exists($this->publicKeyFile) &&
			!$this->option('force')
		) {
			$shouldCreateNewPrivateKey = confirm(
				label: "OpenSSL private key already exists",
				default: false,
				yes: 'Create fresh openssl key pair',
				no: 'Create only a public key based on an existing private key'
			);
		}

		if ($shouldCreateNewPrivateKey) {
			return openssl_pkey_new([
				'private_key_bits' => 2048,
				'private_key_type' => OPENSSL_KEYTYPE_RSA,
			]);
		}

		return openssl_pkey_get_private(file_get_contents($this->privateKeyFile));
	}
}

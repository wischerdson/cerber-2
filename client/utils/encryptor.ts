import type { Ref } from 'vue'
import { pki, util } from 'node-forge'
import { initEncryptionHandshake } from '~/repositories/user'

export type Handshake = {
	server_public_key: string
	client_private_key: string
	handshake_id: string
}

export const defineEncryptor = (handshake: Ref<Handshake | null>) => {
	let keypair: pki.rsa.KeyPair | undefined = void 0

	const getHandshakeOrThrow = () => {
		if (handshake.value) {
			return handshake.value
		}

		throw new Error('Encryption handshake is not defined')
	}

	const getHandshakeId = () => getHandshakeOrThrow().handshake_id

	const initHandshake = async () => {
		const clientKeypair = pki.rsa.generateKeyPair({ bits: 1024 })

		const publicKeyPem = pki.publicKeyToPem(clientKeypair.publicKey)

		const { id: handshake_id, server_public_key } = await initEncryptionHandshake(publicKeyPem)

		handshake.value = {
			server_public_key,
			client_private_key: pki.privateKeyToPem(clientKeypair.privateKey),
			handshake_id
		}
	}

	const getKeypair = () => {
		if (keypair) {
			return keypair
		}

		const handshake = getHandshakeOrThrow()

		return keypair = {
			publicKey: pki.publicKeyFromPem(handshake.server_public_key),
			privateKey: pki.privateKeyFromPem(handshake.client_private_key)
		}
	}

	const initHandshakeIfNeeded = () => {
		if (!handshake.value) {
			return initHandshake()
		}
	}

	const encrypt = (data: string) => util.encode64(getKeypair().publicKey.encrypt(data, 'RSA-OAEP'))

	const decrypt = (data: string) => getKeypair().privateKey.decrypt(data)

	return { initHandshake, initHandshakeIfNeeded, getHandshakeId, encrypt, decrypt }
}

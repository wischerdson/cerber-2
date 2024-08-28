import type { Ref } from 'vue'
import type { Bytes } from 'node-forge'
import { pki, util as forgeUtil, random as forgeRandom, cipher as forgeCipher, hmac as forgeHmac } from 'node-forge'
import { initEncryptionHandshake } from '~/repositories/user'

export type Handshake = {
	server_public_key: string
	client_private_key: string
	handshake_id: string
}

export const defineEncryptor = (handshake: Ref<Handshake | null>) => {
	let keypair: pki.rsa.KeyPair | undefined = void 0

	const key = forgeRandom.getBytesSync(16)
	const iv = forgeRandom.getBytesSync(12)
	const cipher = forgeCipher.createCipher('AES-GCM', key)

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

	const getRsaKeypair = () => {
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

	const computeMac = (value: Bytes) => {
		const hmac = forgeHmac.create()

		hmac.start('sha256', key)
		hmac.update(iv + value)

		return hmac.digest().getBytes();
	}

	const encrypt = (data: string) => {
		cipher.start({ iv, tagLength: 128 })
		cipher.update(forgeUtil.createBuffer(data, 'utf8'))
		cipher.finish()

		const value = cipher.output.getBytes()

		const payload = JSON.stringify({
			iv: forgeUtil.encode64(iv),
			value: forgeUtil.encode64(value),
			mac: forgeUtil.encode64(computeMac(value)),
			tag: forgeUtil.encode64(cipher.mode.tag.getBytes())
		})

		return { payload, key }

	}

	const decrypt = (data: string) => getRsaKeypair().privateKey.decrypt(data)

	return { initHandshake, initHandshakeIfNeeded, getHandshakeId, encrypt, decrypt, getRsaKeypair }
}

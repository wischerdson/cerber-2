import type { AppRequest } from '~/utils/request'
import { useNuxtApp } from '#app'
import { util as forgeUtil } from 'node-forge'

export const encryptRequestDecorator = <T extends AppRequest>(request: T): T => {
	const { $encryptor } = useNuxtApp()

	request.send = () => {
		const body = JSON.stringify(request.getOption('body'))

		const { payload, key } = $encryptor.encrypt(body)
		const encryptedKey = $encryptor.getRsaKeypair().publicKey.encrypt(key)

		request.setOption('body', forgeUtil.encode64(payload))

		request.setHeader('X-Encrypted', 1)
		request.setHeader('X-Handshake-ID', $encryptor.getHandshakeId())
		request.setHeader('X-Key', forgeUtil.encode64(encryptedKey))

		return request.send()
	}

	return request
}

export const decryptResponseDecorator = <T extends AppRequest>(request: T): T => {
	return request
}

import type { AppRequest } from '~/utils/request'
import { useNuxtApp } from '#app'
import { util as forgeUtil } from 'node-forge'

export type EncryptDecoratedRequest<T extends AppRequest> = T & { shouldEncrypt: () => EncryptDecoratedRequest<T> }

export const encryptRequestDecorator = <T extends AppRequest, ReturnT extends EncryptDecoratedRequest<T>>(request: T): ReturnT => {
	const decoratedRequest = request as unknown as ReturnT

	decoratedRequest.shouldEncrypt = () => {
		const { $encryptor } = useNuxtApp()

		const originalSend = request.send

		decoratedRequest.send = () => {
			const body = JSON.stringify(request.getOption('body'))
			const { payload, key } = $encryptor.encrypt(body)
			const encryptedKey = $encryptor.getRsaKeypair().publicKey.encrypt(key)

			request.setOption('body', forgeUtil.encode64(payload))

			request.setHeader('X-Encrypted', 1)
			request.setHeader('X-Handshake-ID', $encryptor.getHandshakeId())
			request.setHeader('X-Key', forgeUtil.encode64(encryptedKey))

			return originalSend()
		}

		return decoratedRequest
	}

	return decoratedRequest
}

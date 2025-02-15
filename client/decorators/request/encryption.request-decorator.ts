import type { AppRequest } from '~/utils/request.types'
import type { DecoratedRequest } from '~/decorators/request'
import { useNuxtApp } from '#app'
import { util as forgeUtil } from 'node-forge'

export type EncryptDecoratedRequest<ObjectT extends AppRequest> = ObjectT & {
	shouldEncrypt: () => EncryptDecoratedRequest<ObjectT>
}

export const decorator = <T extends AppRequest>(request: T) => {
	const decoratedRequest = request as DecoratedRequest<T>

	decoratedRequest.shouldEncrypt = () => {
		const { $encryptor, $config } = useNuxtApp()

		if ($config.public.disableHttpEncryption) {
			return decoratedRequest
		}

		const originalSend = request.send

		decoratedRequest.send = () => {
			const body = JSON.stringify(request.getOption('body'))
			const { payload, key } = $encryptor.encrypt(body)
			const encryptedKey = $encryptor.getRsaKeypair().publicKey.encrypt(key)

			request.setOption('body', forgeUtil.encode64(payload))

			request.setHeader('X-Encrypted', 1)
			request.setHeader('X-Key', forgeUtil.encode64(encryptedKey))

			return originalSend()
		}

		return decoratedRequest
	}

	return decoratedRequest
}

import type { AppRequest } from '~/utils/request'
import { useNuxtApp } from '#app'
import { defaults } from 'lodash-es'
import { util as forgeUtil } from 'node-forge'

type DecoratedRequest<T extends AppRequest> = AuthDecoratedRequest<T> & EncryptDecoratedRequest<T>

type AuthDecoratorParameters = {
	provider?: Parameters<ReturnType<typeof useNuxtApp>['$resolveAuthProvider']>[0],
	ignoreErrors?: boolean
}

type AuthDecoratedRequest<ObjectT extends AppRequest> = ObjectT & {
	sign: (parameters?: AuthDecoratorParameters) => DecoratedRequest<ObjectT>
}

type EncryptDecoratedRequest<ObjectT extends AppRequest> = ObjectT & {
	shouldEncrypt: () => EncryptDecoratedRequest<ObjectT>
}

export const authRequestDecorator = <T extends AppRequest>(request: T) => {
	const decoratedRequest = request as DecoratedRequest<T>

	decoratedRequest.sign = (parameters) => {
		const context = defaults<unknown, Required<AuthDecoratorParameters>>(parameters, {
			provider: 'default',
			ignoreErrors: false
		})

		const provider = useNuxtApp().$resolveAuthProvider(context.provider)
		const originalSend = request.send

		decoratedRequest.send = () => {
			if (provider && !provider.sign(request) && !context.ignoreErrors) {
				return new Promise((_, reject) => reject(null))
			}

			return originalSend()
		}

		return decoratedRequest
	}

	return decoratedRequest
}

export const encryptRequestDecorator = <T extends AppRequest>(request: T) => {
	const decoratedRequest = request as DecoratedRequest<T>

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

export const decryptResponseDecorator = <T extends AppRequest>(request: T): T => {
	return request
}

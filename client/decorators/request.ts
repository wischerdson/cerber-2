import type { AppRequest } from '~/utils/request.types.js'
import { useNuxtApp } from '#app'
import { defaults } from 'lodash-es'
import { util as forgeUtil } from 'node-forge'
import { makeRequestFromFetchContext } from '~/utils/request'

export type DecoratedRequest<T extends AppRequest> = AuthDecoratedRequest<T> & EncryptDecoratedRequest<T>

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

		decoratedRequest.send = async () => {
			if (provider && !(await provider.sign(request)) && !context.ignoreErrors) {
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

export const attachHandshakeIdDecorator = <T extends AppRequest>(request: T): T => {
	const { $encryptor } = useNuxtApp()

	const attachHandshakeId = <RequestT extends AppRequest>(request: RequestT) => {
		let handshakeId = $encryptor.getHandshake()?.handshake_id

		if (handshakeId) {
			request.setHeader('X-Handshake-ID', handshakeId)
		}

		return request
	}

	request.onResponseError(async (context) => {
		if ('error_reason' in context.response._data && context.response._data.error_reason === 'handshake_not_found') {
			await $encryptor.initHandshake()

			return await attachHandshakeId(
				makeRequestFromFetchContext(context)
			).send()
		}
	})

	return attachHandshakeId(request)
}

export const decryptResponseDecorator = <T extends AppRequest>(request: T): T => {
	const { $encryptor } = useNuxtApp()

	request.onResponse(({ response }) => {
		const headers = response.headers

		if (!headers.get('X-Encrypted')) {
			return
		}

		const encryptedKey = headers.get('X-Key')

		if (!encryptedKey) {
			throw new Error('"X-Key" HTTP header required')
		}

		const key = $encryptor.getRsaKeypair().privateKey.decrypt(
			forgeUtil.decode64(encryptedKey)
		)

		const encryptedPayload = JSON.parse(
			forgeUtil.decode64(response._data)
		)

		const contentType = headers.get('Content-Type')

		response._data = $encryptor.decrypt(key, encryptedPayload)

		if (contentType && contentType === 'application/json') {
			response._data = JSON.parse(response._data)
		}
	})

	return request
}

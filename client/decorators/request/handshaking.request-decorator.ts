import type { AppRequest } from '~/utils/request.types'
import { useNuxtApp } from '#app'
import { makeRequestFromFetchContext } from '~/utils/request'

/**
 * Декоратор цепляет к запросу HTTP-заголовок X-Handshake-ID.
 *
 * Если сервер вернул ошибку о том, что X-Handshake-ID невалиден и на сервере не найден,
 * то функция попытается совершить новое рукопожатие с сервером и после этого переотправит
 * исходный запрос снова.
 */
export const decorator = <T extends AppRequest>(request: T): T => {
	const { $encryptor } = useNuxtApp()
	const originalSend = request.send

	const attachHandshakeId = <RequestT extends AppRequest>(request: RequestT) => {
		let handshakeId = $encryptor.getHandshake()?.handshake_id

		if (handshakeId) {
			request.setHeader('X-Handshake-ID', handshakeId)
		}

		return request
	}

	request.send = () => new Promise((resolve, reject) => {
		request.onResponseError(async (context) => {
			const body = context.response._data

			if (
				typeof body === 'object' &&
				'error_reason' in body &&
				body.error_reason === 'handshake_not_found'
			) {
				await $encryptor.initHandshake()

				await attachHandshakeId(makeRequestFromFetchContext(context))
					.send()
					.then(resolve)
					.catch(reject)
			}
		})

		originalSend().then(resolve).catch(reject)
	})

	return attachHandshakeId(request)
}

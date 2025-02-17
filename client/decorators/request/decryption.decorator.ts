import type { AppRequest } from '~/utils/request.types'
import { useNuxtApp } from '#app'
import { util as forgeUtil } from 'node-forge'

export const decorator = <T extends AppRequest>(request: T): T => {
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

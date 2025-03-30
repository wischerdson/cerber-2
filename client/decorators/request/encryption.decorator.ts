import type { RequestDecorator } from '~/utils/request.types'
import { useNuxtApp } from '#app'
import { util as forgeUtil } from 'node-forge'

export const encrypt: RequestDecorator = request => {
	const { $encryptor, $config } = useNuxtApp()

	console.log('encrypt', typeof $config.public.disableHttpEncryption)

	if ($config.public.disableHttpEncryption) {
		return request
	}

	const originalSend = request.send

	request.send = () => {
		const body = JSON.stringify(request.getOption('body'))
		const { payload, key } = $encryptor.encrypt(body)
		const encryptedKey = $encryptor.getRsaKeypair().publicKey.encrypt(key)

		request.setOption('body', forgeUtil.encode64(payload))

		request.setHeader('X-Encrypted', 1)
		request.setHeader('X-Key', forgeUtil.encode64(encryptedKey))

		return originalSend()
	}

	return request
}

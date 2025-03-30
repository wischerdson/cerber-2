import { defineNuxtPlugin } from 'nuxt/app'
import { makeRequest } from '~/utils/request'
import { encryptionHandshake } from '~/decorators/request/handshaking.decorator'
import { decrypt } from '~/decorators/request/decryption.decorator'

export default defineNuxtPlugin(async () => {
	return {
		provide: {
			makeRequest: <DataT>(...args: Parameters<typeof makeRequest<DataT>>) => {
				return makeRequest<DataT>(...args).apply(encryptionHandshake, decrypt)
			}
		}
	}
})

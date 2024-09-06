import { defineNuxtPlugin } from 'nuxt/app'
import { encryptRequestDecorator, decryptResponseDecorator, authRequestDecorator } from '~/decorators/request'
import { makeRequest } from '~/utils/request'

type Args<DataT> = Parameters<typeof makeRequest<DataT>>

export default defineNuxtPlugin(async () => {
	const factory = <DataT>(...args: Args<DataT>) => {
		return authRequestDecorator(
			encryptRequestDecorator(
				decryptResponseDecorator(
					makeRequest<DataT>(...args)
				)
			)
		)
	}

	return {
		provide: {
			makeRequest: factory
		}
	}
})

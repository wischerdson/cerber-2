import { defineNuxtPlugin } from 'nuxt/app'
import { encryptRequestDecorator, decryptResponseDecorator, type EncryptDecoratedRequest } from '~/decorators/crypt'
import { authRequestDecorator, type AuthDecoratedRequest } from '~/decorators/auth'
import { makeRequest, type AppRequest } from '~/utils/request'

// type DecoratedRequest<DataT, RequestT extends AppRequest = AppRequest<DataT>> =
// 	RequestT &
// 	EncryptDecoratedRequest<DecoratedRequest<DataT, RequestT>> &
// 	AuthDecoratedRequest<RequestT>

type Args<DataT> = Parameters<typeof makeRequest<DataT>>

export default defineNuxtPlugin(async () => {
	const decorators = [
		decryptResponseDecorator,
		encryptRequestDecorator,
		authRequestDecorator
	]

	const factory = <DataT>(...args: Args<DataT>) => {
		return authRequestDecorator(
			encryptRequestDecorator(
				decryptResponseDecorator(
					makeRequest(...args)
				)
			)
		)
		// return decorators.reduce(
		// 	(request, decorator) => decorator(request),
		// 	makeRequest(...args)
		// ) as DecoratedRequest<DataT>
	}

	return {
		provide: {
			makeRequest: factory
		}
	}
})

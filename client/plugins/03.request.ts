import type { AppRequest } from '~/utils/request.types.ts'
import type { DecoratedRequest } from '~/decorators/request'
import { defineNuxtPlugin } from 'nuxt/app'
import { encryptRequestDecorator, decryptResponseDecorator, authRequestDecorator, attachHandshakeIdDecorator } from '~/decorators/request'
import { makeRequest, makeRequestFromFetchContext } from '~/utils/request'

export default defineNuxtPlugin(async () => {
	const wrapWithDecorators = <RequestT extends AppRequest>(request: RequestT): DecoratedRequest<RequestT> => {
		return authRequestDecorator(
			encryptRequestDecorator(
				decryptResponseDecorator(
					attachHandshakeIdDecorator(
						request
					)
				)
			)
		)
	}

	return {
		provide: {
			makeRequest: <DataT>(...args: Parameters<typeof makeRequest<DataT>>) => {
				return wrapWithDecorators(makeRequest<DataT>(...args))
			},
			makeRequestFromFetchContext: <DataT>(...args: Parameters<typeof makeRequestFromFetchContext<DataT>>) => {
				return wrapWithDecorators(makeRequestFromFetchContext(...args))
			}
		}
	}
})

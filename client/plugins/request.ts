import { defineNuxtPlugin } from 'nuxt/app'
import { encryptRequestDecorator, decryptResponseDecorator } from '~/decorators/crypt'
import { authRequestDecorator, type AuthDecoratorParameters } from '~/decorators/auth'
import { makeRequest, type AppRequest } from '~/utils/request'

type DecoratedRequest<DataT> = AppRequest<DataT> & {
	shouldEncrypt: () => DecoratedRequest<DataT>
	sign: (parameters?: AuthDecoratorParameters) => DecoratedRequest<DataT>
}

type Args<DataT> = Parameters<typeof makeRequest<DataT>>

export default defineNuxtPlugin(async () => {
	const factory = <DataT>(...args: Args<DataT>) => {
		let request = makeRequest(...args) as DecoratedRequest<DataT>

		request.shouldEncrypt = () => encryptRequestDecorator(request)
		request.sign = parameters => authRequestDecorator(request, parameters)
		request = decryptResponseDecorator(request)

		return request
	}

	return {
		provide: {
			makeRequest: factory
		}
	}
})

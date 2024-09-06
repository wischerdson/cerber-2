import type { AppRequest } from '~/utils/request'
import { useNuxtApp } from '#app'
import { defaults } from 'lodash-es'

type AuthDecoratorParameters = {
	provider?: Parameters<ReturnType<typeof useNuxtApp>['$resolveAuthProvider']>[0],
	ignoreErrors?: boolean
}

export type AuthDecoratedRequest<ObjectT, ReturnT> = ObjectT & { sign: (parameters?: AuthDecoratorParameters) => ObjectT & ReturnT }

export const authRequestDecorator = <T extends AppRequest, ReturnT>(request: T): ReturnT => {
	const decoratedRequest = request as unknown as AuthDecoratedRequest<T, ReturnT>

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

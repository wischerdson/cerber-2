import type { AppRequest } from '~/utils/request'
import { useNuxtApp } from '#app'
import { defaults } from 'lodash-es'

type AuthDecoratorParameters = {
	provider?: Parameters<ReturnType<typeof useNuxtApp>['$resolveAuthProvider']>[0],
	ignoreErrors?: boolean
}

export type AuthDecoratedRequest<T extends AppRequest> = T & { sign: (parameters?: AuthDecoratorParameters) => AuthDecoratedRequest<T> }

export const authRequestDecorator = <T extends AppRequest>(request: T): AuthDecoratedRequest<T> => {
	const decoratedRequest: AuthDecoratedRequest<T> = {
		...request,
		sign(parameters) {
			const { ignoreErrors, provider } = defaults(parameters, {
				provider: 'default',
				ignoreErrors: false
			})
			const authProvider = useNuxtApp().$resolveAuthProvider(provider)
			const originalSend = request.send

			request.send = () => {
				if (authProvider && !authProvider.sign(request) && !ignoreErrors) {
					return new Promise((_, reject) => reject(null))
				}

				return originalSend()
			}

			return decoratedRequest
		}
	}

	return decoratedRequest
}

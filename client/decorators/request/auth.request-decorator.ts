import type { AppRequest } from '~/utils/request.types'
import type { DecoratedRequest } from '~/decorators/request'
import { useNuxtApp } from '#app'
import { defaults } from 'lodash-es'

type AuthDecoratorParameters = {
	provider?: Parameters<ReturnType<typeof useNuxtApp>['$resolveAuthProvider']>[0],
	ignoreErrors?: boolean
}

export type AuthDecoratedRequest<ObjectT extends AppRequest> = ObjectT & {
	sign: (parameters?: AuthDecoratorParameters) => DecoratedRequest<ObjectT>
}

export const decorator = <T extends AppRequest>(request: T) => {
	const decoratedRequest = request as DecoratedRequest<T>

	decoratedRequest.sign = (parameters) => {
		const context = defaults<unknown, Required<AuthDecoratorParameters>>(parameters, {
			provider: 'default',
			ignoreErrors: false
		})

		const provider = useNuxtApp().$resolveAuthProvider(context.provider)
		const originalSend = request.send

		decoratedRequest.send = async () => {
			if (provider && !(await provider.sign(request)) && !context.ignoreErrors) {
				return new Promise((_, reject) => reject(null))
			}

			return originalSend()
		}

		return decoratedRequest
	}

	return decoratedRequest
}

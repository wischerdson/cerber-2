import type { AppRequest } from '~/utils/request'
import { useNuxtApp } from '#app'
import { defaults } from 'lodash-es'

export type RawAuthProvider = Parameters<ReturnType<typeof useNuxtApp>['$resolveAuthProvider']>[0]

export type AuthDecoratorParameters = {
	provider?: RawAuthProvider,
	ignoreErrors?: boolean
}

export const authRequestDecorator = <T extends AppRequest>(request: T, parameters?: AuthDecoratorParameters): T => {
	const { ignoreErrors, provider } = defaults(parameters, {
		provider: 'default',
		ignoreErrors: false
	})

	const authProvider = useNuxtApp().$resolveAuthProvider(provider)

	request.send = () => {
		if (authProvider && !authProvider.sign(request) && !ignoreErrors) {
			return new Promise((_, reject) => reject(null))
		}

		return request.send()
	}

	return request
}

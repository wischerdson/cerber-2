import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import type { AsyncDataOptions, AsyncData, NuxtError } from 'nuxt/app'
import type { AuthProvider } from './auth-providers'
import type { FetchError } from 'ofetch'
import type { KeysOf, PickFrom } from '#app/composables/asyncData'
import { useNuxtApp } from 'nuxt/app'
import { apiBaseUrl } from './helpers'
import { defaults } from 'lodash-es'
import { useAsyncData, } from 'nuxt/app'

export type AuthType = Parameters<ReturnType<typeof useNuxtApp>['$auth']>[0]

export type Options<DataT, RequestT extends NitroFetchRequest> = AsyncDataOptions<DataT> & NitroFetchOptions<RequestT> & { key?: string }

export interface AppRequest<RequestT extends NitroFetchRequest, DataT, ErrorT = FetchError | null> {
	setOption<K extends keyof Options<DataT, RequestT>>(name: K, value: Options<DataT, RequestT>[K]): AppRequest<RequestT, DataT, ErrorT>
	getOption<K extends keyof Options<DataT, RequestT>>(name: K): Options<DataT, RequestT>[K]
	setHeader(name: string, value?: string | null): AppRequest<RequestT, DataT, ErrorT>
	key(value: string): AppRequest<RequestT, DataT, ErrorT>
	sign(authProvider: AuthProvider | AuthType): AppRequest<RequestT, DataT, ErrorT>
	send(): AsyncData<PickFrom<DataT, KeysOf<DataT>> | null, ErrorT | NuxtError<ErrorT> | null>
}

export const makeRequest = <
	DataT = unknown,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
> (url: RequestT, opts?: Options<DataT, RequestT>) => {
	let auth: AuthProvider

	const options = defaults(opts || {}, {
		baseURL: apiBaseUrl(),
		ignoreResponseError: true,
		immediate: true,
		mode: 'cors',
		server: true
	}) as Options<DataT, RequestT>

	const request: AppRequest<RequestT, DataT, ErrorT> = {
		setOption(name, value) {
			value === undefined ? delete options[name] : options[name] = value

			return request
		},
		getOption(name) {
			return options[name]
		},
		setHeader(name, value) {
			request.setOption('headers', Object.assign({ [name]: value }, options.headers))

			return request
		},
		key(value: string) {
			request.setOption('key', value)

			return request
		},
		sign(authProvider) {
			if (typeof authProvider === 'string') {
				const { $auth } = useNuxtApp()
				authProvider = $auth(authProvider)
			}

			auth = authProvider

			return request
		},
		send() {
			if (auth && auth.canSign()) {
				auth.sign(request)
			}

			if (options.hasOwnProperty('key') && options.key) {
				return useAsyncData<DataT, ErrorT>(options.key, () => $fetch<DataT, RequestT>(url, options), options)
			}

			return useAsyncData<DataT, ErrorT>(() => $fetch<DataT, RequestT>(url, options), options)
		}
	}

	return request
}

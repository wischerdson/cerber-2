import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import type { AsyncDataOptions, AsyncData, NuxtError } from 'nuxt/app'
import type { AuthProvider, AuthType } from '~/utils/auth'
import type { FetchError } from 'ofetch'
import type { KeysOf, PickFrom } from '#app/composables/asyncData'
import { resolveAuthProvider } from '~/utils/auth'
import { apiBaseUrl } from '~/utils/helpers'
import { defaults } from 'lodash-es'
import { useAsyncData } from 'nuxt/app'

export type AsyncDataResponse<DataT, ErrorT = FetchError> = AsyncData<PickFrom<DataT, KeysOf<DataT>> | null, ErrorT | NuxtError<ErrorT> | null>

export type Options<DataT, RequestT extends NitroFetchRequest> = AsyncDataOptions<DataT> & NitroFetchOptions<RequestT>

export interface AppRequest<DataT, ErrorT, ResponseT, RequestT extends NitroFetchRequest = NitroFetchRequest> {
	setOption<K extends keyof Options<DataT, RequestT>>(name: K, value: Options<DataT, RequestT>[K]): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	getOption<K extends keyof Options<DataT, RequestT>>(name: K): Options<DataT, RequestT>[K]
	setHeader(name: string, value?: string | null): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	asAsyncData(key: string, opts?: AsyncDataOptions<DataT>): AppRequest<DataT, ErrorT, AsyncDataResponse<DataT, ErrorT>, RequestT>
	sign(authProvider: AuthProvider | AuthType): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	send(): ResponseT
}

export const makeRequest = <
	DataT = unknown,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
> (url: RequestT, opts?: NitroFetchOptions<RequestT>) => {
	const options = defaults<unknown, Options<DataT, RequestT>>(opts, {
		baseURL: apiBaseUrl(),
		mode: 'cors'
	})

	let asyncDataKey: string
	let auth: AuthProvider

	const request: AppRequest<DataT, ErrorT, AsyncDataResponse<DataT, ErrorT> | Promise<DataT>, RequestT> = {
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
		asAsyncData(key, asyncDataOpts) {
			asyncDataKey = key

			Object.assign(options, asyncDataOpts, {
				immediate: true,
				server: true
			})

			return request as unknown as AppRequest<DataT, ErrorT, AsyncDataResponse<DataT, ErrorT>, RequestT>
		},
		sign(authProvider) {
			auth = resolveAuthProvider(authProvider)

			return request
		},
		send() {
			if (auth && auth.canSign()) {
				auth.sign(request)
			}

			const fetch = () => $fetch<DataT>(url, options)

			if (asyncDataKey) {
				return useAsyncData<DataT, ErrorT>(asyncDataKey, fetch, options)
			}

			return fetch()
		}
	}

	return request as AppRequest<DataT, ErrorT, Promise<DataT>, RequestT>
}

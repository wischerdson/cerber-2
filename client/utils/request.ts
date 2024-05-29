import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import type { AsyncDataOptions, AsyncData, NuxtError } from 'nuxt/app'
import type { FetchError } from 'ofetch'
import type { KeysOf, PickFrom } from '#app/composables/asyncData'
import { apiBaseUrl } from '~/utils/helpers'
import { defaults } from 'lodash-es'
import { useAsyncData } from 'nuxt/app'

export type AsyncDataResponse<DataT, ErrorT = FetchError> = AsyncData<PickFrom<DataT, KeysOf<DataT>> | null, ErrorT | NuxtError<ErrorT> | null>

export type Options<DataT, RequestT extends NitroFetchRequest> = AsyncDataOptions<DataT> & NitroFetchOptions<RequestT>

export interface AppRequest<DataT = any, ErrorT = any, ResponseT = any, RequestT extends NitroFetchRequest = NitroFetchRequest> {
	setOption<K extends keyof Options<DataT, RequestT>>(name: K, value: Options<DataT, RequestT>[K]): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	getOption<K extends keyof Options<DataT, RequestT>>(name: K): Options<DataT, RequestT>[K]
	setHeader(name: string, value?: string | null): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	setBearerToken(token: string): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	asAsyncData(key: string, opts?: AsyncDataOptions<DataT>): AppRequest<DataT, ErrorT, AsyncDataResponse<DataT, ErrorT>, RequestT>
	as(authProvider: undefined, strict: boolean): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	send(): ResponseT
}

export const makeRequest = <
	DataT = unknown,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
> (url: RequestT, opts?: NitroFetchOptions<RequestT>) => {
	const options = defaults<unknown, Options<DataT, RequestT>>(opts, {
		headers: {},
		baseURL: apiBaseUrl(),
		mode: 'cors'
	})

	let asyncDataKey: string

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
		setBearerToken(token) {
			request.setHeader('Authorization', `Bearer ${token}`)

			return request
		},
		asAsyncData(key, asyncDataOpts) {
			asyncDataKey = key

			Object.assign(options, {
				immediate: true,
				server: true
			}, asyncDataOpts)

			return request as unknown as AppRequest<DataT, ErrorT, AsyncDataResponse<DataT, ErrorT>, RequestT>
		},
		as(authProvider, strict = true) {
			if (user) {
				// request.setBearerToken(`${user.id}:${user.token}`)
			} else if (throwIfUndefined) {
				throw new Error('User is not defined')
			}

			return request
		},
		send() {
			const fetch = () => $fetch<DataT>(url, options)

			if (asyncDataKey) {
				return useAsyncData<DataT, ErrorT>(asyncDataKey, fetch, options)
			}

			return fetch()
		}
	}

	return request as AppRequest<DataT, ErrorT, Promise<DataT>, RequestT>
}

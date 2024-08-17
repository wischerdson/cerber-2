import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import type { AsyncDataOptions, AsyncData, NuxtError } from 'nuxt/app'
import type { FetchError } from 'ofetch'
import type { KeysOf, PickFrom } from '#app/composables/asyncData'
import type { AuthProvider } from './auth'
import type { AuthType } from '~/composables/use-auth'
import { useAuth } from '~/composables/use-auth'
import { apiBaseUrl } from '~/utils/helpers'
import { defaults } from 'lodash-es'
import { useAsyncData, useNuxtApp } from 'nuxt/app'

export type AsyncDataResponse<DataT, ErrorT = FetchError> = AsyncData<PickFrom<DataT, KeysOf<DataT>> | null, ErrorT | NuxtError<ErrorT> | null>

export type Options<DataT, RequestT extends NitroFetchRequest> = AsyncDataOptions<DataT> & NitroFetchOptions<RequestT>

export interface AppRequest<DataT = any, ErrorT = any, ResponseT = any, RequestT extends NitroFetchRequest = NitroFetchRequest> {
	setOption<K extends keyof Options<DataT, RequestT>>(name: K, value: Options<DataT, RequestT>[K]): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	getOption<K extends keyof Options<DataT, RequestT>>(name: K): Options<DataT, RequestT>[K]
	setHeader(name: string, value?: number | string | null): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	setBearerToken(token: string): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	asAsyncData(key: string, opts?: AsyncDataOptions<DataT>): AppRequest<DataT, ErrorT, AsyncDataResponse<DataT, ErrorT>, RequestT>
	sign(authProvider: AuthType, strict?: boolean): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	shouldEncrypt(): AppRequest<DataT, ErrorT, ResponseT, RequestT>
	send(): Promise<ResponseT>
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

	const { $encryptor } = useNuxtApp()

	let asyncDataKey: string
	let authProvider: AuthProvider
	let stopIfCannotSign: boolean = false
	let shouldEncrypt: boolean = false

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
		sign(_authProvider, _stopIfCannotSign = true) {
			authProvider = useAuth(_authProvider)
			stopIfCannotSign = _stopIfCannotSign

			return request
		},
		shouldEncrypt() {
			shouldEncrypt = true

			return request
		},
		send() {
			return new Promise(async (resolve, reject) => {
				if (authProvider) {
					try {
						await authProvider.sign(request)
					} catch (reason) {
						authProvider.logout()

						if (stopIfCannotSign) {
							return null
						}
					}
				}

				shouldEncrypt && $encryptor.encrypt(request)

				const fetch = () => $fetch<DataT>(url, options)

				try {
					const result = await asyncDataKey ?
						useAsyncData<DataT, ErrorT>(asyncDataKey, fetch, options) :
						fetch()

					resolve(result)
				} catch (error) {
					reject(error)
				}
			})
		}
	}

	return request as AppRequest<DataT, ErrorT, Promise<DataT>, RequestT>
}

import type { AuthProvider } from './auth-providers'
import type { Ref } from 'vue'
import type { FetchError } from 'ofetch'
import type { UseFetchOptions } from 'nuxt/app'
import { defaultsDeep } from 'lodash'
import { useFetch, useNuxtApp } from 'nuxt/app'
import { apiBaseUrl } from './helpers'

type Url = string | Request | Ref<string | Request> | (() => string | Request)

interface RequestState<DataT> {
	url: Url
	options: UseFetchOptions<DataT>
	auth: AuthProvider | null
}

export type AuthType = Parameters<ReturnType<typeof useNuxtApp>['$auth']>[0]

export interface AppRequest<DataT, ErrorT = FetchError | null> {
	setOption<K extends keyof UseFetchOptions<DataT>>(name: K, value: UseFetchOptions<DataT>[K] | undefined): AppRequest<DataT, ErrorT>
	getOption<K extends keyof UseFetchOptions<DataT>>(name: K): UseFetchOptions<DataT>[K]
	setHeader(name: string, value: string | null | undefined): AppRequest<DataT, ErrorT>
	sign(authProvider: AuthProvider | AuthType): AppRequest<DataT, ErrorT>
	send(): ReturnType<typeof useFetch<DataT, ErrorT>>
}

export const makeRequest = <DataT, ErrorT = FetchError | null>(url: Url, options: UseFetchOptions<DataT, ErrorT> = {}): AppRequest<DataT, ErrorT> => {
	const defaultOptions: UseFetchOptions<DataT> = {
		watch: false,
		baseURL: apiBaseUrl(),
		key: typeof url === 'string' ? url : void 0,
		server: true,
		mode: 'cors'
	}

	options = defaultsDeep(options, defaultOptions)

	const state: RequestState<DataT> = { url, options, auth: null }

	const request: AppRequest<DataT, ErrorT> = {
		setOption(name, value) {
			value === undefined ? delete state.options[name] : state.options[name] = value
			return request
		},
		getOption(name) {
			return state.options[name]
		},
		setHeader(name, value) {
			request.setOption('headers', Object.assign({ [name]: value }, state.options.headers))
			return request
		},
		sign(authProvider) {
			if (typeof authProvider === 'string') {
				const { $auth } = useNuxtApp()
				authProvider = $auth(authProvider)
			}

			state.auth = authProvider
			return request
		},
		send() {
			if (state.auth && state.auth.canSign()) {
				state.auth.sign(request)
			}

			return useFetch(state.url, state.options)
		}
	}

	return request
}

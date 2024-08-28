import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import { apiBaseUrl } from '~/utils/helpers'
import { defaults } from 'lodash-es'

type Interceptors<RequestT extends NitroFetchRequest> = {
	onRequest: Options<RequestT>['onRequest']
	onRequestError: Options<RequestT>['onRequestError']
	onResponse: Options<RequestT>['onResponse']
	onResponseError: Options<RequestT>['onResponseError']
}

type Options<RequestT extends NitroFetchRequest> = NitroFetchOptions<RequestT> & {
	headers: Headers
}

export type InitRequestOptions<RequestT extends NitroFetchRequest> = Omit<
	Options<RequestT>,
	(keyof Interceptors<RequestT>) | 'headers'
> & { headers?: Headers }

export interface AppRequest<
	DataT = unknown,
	ResponseT extends Promise<DataT> = Promise<DataT>,
	RequestT extends NitroFetchRequest = NitroFetchRequest
> {
	setOption<K extends keyof Options<RequestT>>(name: K, value: Options<RequestT>[K]): AppRequest<DataT, ResponseT, RequestT>
	getOption<K extends keyof Options<RequestT>>(name: K): Options<RequestT>[K]
	setHeader(name: string, value: number | string | null): AppRequest<DataT, ResponseT, RequestT>
	getHeader(name: string): string | null
	setBearerToken(token: string): AppRequest<DataT, ResponseT, RequestT>
	onRequest(interceptor: Interceptors<RequestT>['onRequest']): AppRequest<DataT, ResponseT, RequestT>
	onResponse(interceptor: Interceptors<RequestT>['onResponse']): AppRequest<DataT, ResponseT, RequestT>
	onRequestError(interceptor: Interceptors<RequestT>['onRequestError']): AppRequest<DataT, ResponseT, RequestT>
	onResponseError(interceptor: Interceptors<RequestT>['onResponseError']): AppRequest<DataT, ResponseT, RequestT>
	send: () => ResponseT
}

export const makeRequest = <
	DataT = unknown,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(url: RequestT, opts?: InitRequestOptions<RequestT>) => {
	const options = defaults<unknown, Options<RequestT>>(opts, {
		headers: new Headers(),
		baseURL: apiBaseUrl(),
		mode: 'cors',
		onRequest (ctx) {
			interceptors.onRequest.forEach(cb => typeof cb === 'function' && cb(ctx))
		},
		onRequestError (ctx) {
			interceptors.onRequestError.forEach(cb => typeof cb === 'function' && cb(ctx))
		},
		onResponse (ctx) {
			interceptors.onResponse.forEach(cb => typeof cb === 'function' && cb(ctx))
		},
		onResponseError (ctx) {
			interceptors.onResponseError.forEach(cb => typeof cb === 'function' && cb(ctx))
		}
	})

	const interceptors: { [key in keyof Interceptors<RequestT>]: Interceptors<RequestT>[key][] } = {
		onResponse: [],
		onRequest: [],
		onResponseError: [],
		onRequestError: [],
	}

	const request: AppRequest<DataT, Promise<DataT>, RequestT> = {
		setOption(name, value) {
			value === void 0 ? delete options[name] : options[name] = value

			return request
		},
		getOption(name) {
			return options[name]
		},
		setHeader(name, value) {
			value === null ? options.headers.delete(name) : options.headers.set(name, value.toString())

			return request
		},
		getHeader(name) {
			return options.headers.get(name)
		},
		setBearerToken(token) {
			request.setHeader('Authorization', `Bearer ${token}`)

			return request
		},
		onRequest(interceptor) {
			interceptors.onRequest.push(interceptor)

			return request
		},
		onResponse(interceptor) {
			interceptors.onResponse.push(interceptor)

			return request
		},
		onRequestError(interceptor) {
			interceptors.onRequestError.push(interceptor)

			return request
		},
		onResponseError(interceptor) {
			interceptors.onResponseError.push(interceptor)

			return request
		},
		send() {
			return $fetch<DataT>(url, options)
		}
	}

	return request
}

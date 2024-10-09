import type { InterceptorHelper } from './request.d.ts'
import { apiBaseUrl } from '~/utils/helpers'
import { defaults } from 'lodash-es'

const interceptorsHelper: InterceptorHelper = (interceptors, ctx) => {
	const promises: Promise<unknown>[] = []
	interceptors.forEach(
		cb => typeof cb === 'function' && promises.push(Promise.resolve(cb(ctx)))
	)

	return Promise.all(promises)
}

export const makeRequest = <
	DataT = unknown,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(url: RequestT, options?: Options<RequestT>) => {
	const context = makeContext(options)

	const request: AppRequest<DataT, Promise<DataT>, RequestT> = {
		setOption(name, value) {
			value === void 0 ? delete context.options[name] : context.options[name] = value

			return request
		},
		getOption(name) {
			return context.options[name]
		},
		setHeader(name, value) {
			value === null ? context.headers.delete(name) : context.headers.set(name, value.toString())

			return request
		},
		getHeader(name) {
			return context.headers.get(name)
		},
		setBearerToken(token) {
			request.setHeader('Authorization', `Bearer ${token}`)

			return request
		},
		onRequest(interceptor) {
			// context.interceptors.onRequest.push(interceptor)

			return request
		},
		onResponse(interceptor) {
			// context.interceptors.onResponse.push(interceptor)

			return request
		},
		onRequestError(interceptor) {
			// context.interceptors.onRequestError.push(interceptor)

			return request
		},
		onResponseError(interceptor) {
			// context.interceptors.onResponseError.push(interceptor)

			return request
		},
		send() {
			return $fetch<DataT>(
				url,
				Object.assign(context.options, context.interceptors, { headers: context.headers })
			)
		}
	}

	return request
}

const makeContext = <RequestT extends NitroFetchRequest>(options?: Options<RequestT>) => {
	const context = {
		interceptors: {
			onResponse: [],
			onRequest: [],
			onResponseError: [],
			onRequestError: [],
		} as Interceptors<RequestT>,
		headers: new Headers(options?.headers),
		options: defaults(options, {
			headers: new Headers(),
			baseURL: apiBaseUrl(),
			mode: 'cors'
		}) as Options<RequestT>
	}

	return context
}

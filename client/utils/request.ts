import type { CallInterceptors, AppRequest, Options, MakeContext, AppRequestContext } from './request.types.ts'
import type { NitroFetchRequest } from 'nitropack'
import type { FetchContext } from 'ofetch'
import { apiBaseUrl } from '~/utils/helpers'
import { defaults } from 'lodash-es'

export const makeRequest = <
	DataT = unknown,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(url: RequestT, options?: Options<RequestT>) => {
	const context = makeContext(options)

	const request: AppRequest<DataT, Promise<DataT>, RequestT> = {
		_context: context,
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
			return request.setHeader('Authorization', `Bearer ${token}`)
		},
		onRequest(interceptor) {
			context.interceptors.onRequest.push(interceptor)

			return request
		},
		onResponse(interceptor) {
			context.interceptors.onResponse.push(interceptor)

			return request
		},
		onRequestError(interceptor) {
			context.interceptors.onRequestError.push(interceptor)

			return request
		},
		onResponseError(interceptor) {
			context.interceptors.onResponseError.push(interceptor)

			return request
		},
		send() {
			return $fetch<DataT>(url, compileRequestOptions(context))
		}
	}

	return request
}

export const makeRequestFromFetchContext = <DataT = unknown, RequestT extends NitroFetchRequest = NitroFetchRequest>(context: FetchContext<DataT>) => {
	return makeRequest<DataT>(context.request, context.options as Options<RequestT>)
}

const makeContext: MakeContext = (options) => {
	const context = {
		interceptors: {
			onResponse: [],
			onRequest: [],
			onResponseError: [],
			onRequestError: [],
		},
		headers: new Headers(options?.headers),
		options: defaults(options, {
			baseURL: apiBaseUrl(),
			mode: 'cors'
		})
	}

	return context
}

const compileRequestOptions = <RequestT extends NitroFetchRequest>(context: AppRequestContext<RequestT>): Options<RequestT> => {
	const options = { ...context.options }

	options.headers = mergeHeaders(context.options.headers, context.headers)

	options.onRequest = ctx => callInterceptors<RequestT>(context, 'onRequest', ctx)
	options.onResponse = ctx => callInterceptors<RequestT>(context, 'onResponse', ctx)
	options.onRequestError = ctx => callInterceptors<RequestT>(context, 'onRequestError', ctx)
	options.onResponseError = ctx => callInterceptors<RequestT>(context, 'onResponseError', ctx)

	return options
}

const callInterceptors: CallInterceptors = (context, type, ctx) => {
	const promises: Promise<void>[] = []
	let cbList: any[] = []

	if (type in context.options) {
		const interceptors = context.options[type]

		cbList = cbList.concat(Array.isArray(interceptors) ? interceptors : [interceptors])
	}

	cbList
		.concat(context.interceptors[type])
		.forEach(cb => promises.push(Promise.resolve(cb(ctx))))

	return Promise.all(promises) as unknown as Promise<void>
}

const mergeHeaders = (src1?: HeadersInit, src2?: HeadersInit) => {
	const result = new Headers(src1)

	new Headers(src2).forEach((value, name) => result.set(name, value))

	return result
}

import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import type { FetchHooks } from 'ofetch'

export type Options<RequestT extends NitroFetchRequest> = NitroFetchOptions<RequestT>

export type Interceptors = {
	onRequest: ElementType<Exclude<FetchHooks['onRequest'], undefined>>
	onRequestError: ElementType<Exclude<FetchHooks['onRequestError'], undefined>>
	onResponse: ElementType<Exclude<FetchHooks['onResponse'], undefined>>
	onResponseError: ElementType<Exclude<FetchHooks['onResponseError'], undefined>>
}

export type CallInterceptors = <RequestT extends NitroFetchRequest>(
	context: AppRequestContext<RequestT>,
	type: 'onRequest' | 'onResponse' | 'onRequestError' | 'onResponseError',
	ctx: any
) => Promise<void>

export type MakeContext = <RequestT extends NitroFetchRequest>(options?: Options<RequestT>) => AppRequestContext<RequestT>

export interface AppRequest<
	DataT = unknown,
	ResponseT = Promise<DataT>,
	RequestT extends NitroFetchRequest = NitroFetchRequest
> {
	_context: AppRequestContext<RequestT>
	setOption<K extends keyof Options<RequestT>>(name: K, value: Options<RequestT>[K]): AppRequest<DataT, ResponseT, RequestT>
	getOption<K extends keyof Options<RequestT>>(name: K): Options<RequestT>[K]
	setHeader(name: string, value: number | string | null): AppRequest<DataT, ResponseT, RequestT>
	getHeader(name: string): string | null
	setBearerToken(token: string): AppRequest<DataT, ResponseT, RequestT>
	onRequest(interceptor: Interceptors['onRequest']): AppRequest<DataT, ResponseT, RequestT>
	onResponse(interceptor: Interceptors['onResponse']): AppRequest<DataT, ResponseT, RequestT>
	onRequestError(interceptor: Interceptors['onRequestError']): AppRequest<DataT, ResponseT, RequestT>
	onResponseError(interceptor: Interceptors['onResponseError']): AppRequest<DataT, ResponseT, RequestT>
	send(): ResponseT
}

export interface AppRequestContext<RequestT extends NitroFetchRequest> {
	interceptors: { [key in keyof Interceptors]: Interceptors[key][] }
	headers: Headers
	options: Options<RequestT>
}

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

export type MakeContext = <RequestT extends NitroFetchRequest>(url?: RequestT, options?: Options<RequestT>) => AppRequestContext<RequestT>

export type AppRequest<
	DataT = unknown,
	RequestT extends NitroFetchRequest = NitroFetchRequest
> = {
	_context: AppRequestContext<RequestT>
	apply(...decorators: RequestDecorator[]): AppRequest<DataT, RequestT>
	url(url: RequestT): AppRequest<DataT, RequestT>
	query(query: Options<RequestT>['query']): AppRequest<DataT, RequestT>
	body(body: Options<RequestT>['body']): AppRequest<DataT, RequestT>
	setOption<K extends keyof Options<RequestT>>(name: K, value: Options<RequestT>[K]): AppRequest<DataT, RequestT>
	getOption<K extends keyof Options<RequestT>>(name: K): Options<RequestT>[K]
	setHeader(name: string, value: number | string | null): AppRequest<DataT, RequestT>
	getHeader(name: string): string | null
	setBearerToken(token: string): AppRequest<DataT, RequestT>
	onRequest(interceptor: Interceptors['onRequest']): AppRequest<DataT, RequestT>
	onResponse(interceptor: Interceptors['onResponse']): AppRequest<DataT, RequestT>
	onRequestError(interceptor: Interceptors['onRequestError']): AppRequest<DataT, RequestT>
	onResponseError(interceptor: Interceptors['onResponseError']): AppRequest<DataT, RequestT>
	send(): Promise<DataT>
}

export interface AppRequestContext<RequestT extends NitroFetchRequest> {
	interceptors: { [key in keyof Interceptors]: Interceptors[key][] }
	headers: Headers
	options: Options<RequestT>
	url?: RequestT
}

export type RequestDecorator = <RequestT extends AppRequest>(
	request: RequestT,
	parameters?: { [key: string]: unknown }
) => RequestT

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
	RequestT extends NitroFetchRequest = NitroFetchRequest,
	ExtendedT = {},
> = {
	_context: AppRequestContext<RequestT>
	extends<ExtendedK>(): AppRequest<DataT, RequestT, ExtendedT & ExtendedK>
	url(url: RequestT): AppRequest<DataT, RequestT, ExtendedT>
	query(query: Options<RequestT>['query']): AppRequest<DataT, RequestT, ExtendedT>
	setOption<K extends keyof Options<RequestT>>(name: K, value: Options<RequestT>[K]): AppRequest<DataT, RequestT, ExtendedT>
	getOption<K extends keyof Options<RequestT>>(name: K): Options<RequestT>[K]
	setHeader(name: string, value: number | string | null): AppRequest<DataT, RequestT, ExtendedT>
	getHeader(name: string): string | null
	setBearerToken(token: string): AppRequest<DataT, RequestT, ExtendedT>
	onRequest(interceptor: Interceptors['onRequest']): AppRequest<DataT, RequestT, ExtendedT>
	onResponse(interceptor: Interceptors['onResponse']): AppRequest<DataT, RequestT, ExtendedT>
	onRequestError(interceptor: Interceptors['onRequestError']): AppRequest<DataT, RequestT, ExtendedT>
	onResponseError(interceptor: Interceptors['onResponseError']): AppRequest<DataT, RequestT, ExtendedT>
	send(): Promise<DataT>
} & ExtendedT

export interface AppRequestContext<RequestT extends NitroFetchRequest> {
	interceptors: { [key in keyof Interceptors]: Interceptors[key][] }
	headers: Headers
	options: Options<RequestT>
	url?: RequestT
}

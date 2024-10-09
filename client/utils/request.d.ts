import type { NitroFetchRequest, NitroFetchOptions } from 'nitropack'
import { createFetchError } from 'ofetch'

declare namespace request {
	export type SomeType = string;



	export type Options<RequestT extends NitroFetchRequest> = NitroFetchOptions<RequestT>

	export type Interceptors<RequestT extends NitroFetchRequest> = {
		onRequest: Options<RequestT>['onRequest']
		onRequestError: Options<RequestT>['onRequestError']
		onResponse: Options<RequestT>['onResponse']
		onResponseError: Options<RequestT>['onResponseError']
	}

	export type FetchContext<T = any> = Parameters<typeof createFetchError<T>>[0]

	export type InterceptorHelper = <
		TypeT extends 'onRequest' | 'onResponse' | 'onRequestError' | 'onResponseError',
		RequestT extends NitroFetchRequest
	>(interceptors: Interceptors<RequestT>[TypeT][], ctx: any) => Promise<unknown>

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
		send(): ResponseT
	}

}

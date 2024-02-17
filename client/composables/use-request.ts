import type { FetchError } from 'ofetch'
import type { Options } from '~/utils/request'
import type { NitroFetchRequest } from 'nitropack'
import { makeRequest } from '~/utils/request'

export const useGetReq = <
	DataT,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(request: RequestT, options: Options<DataT, RequestT> = {}) => {
	return makeRequest<DataT, ErrorT>(request, { method: 'GET', ...options })
}

export const usePostReq = <
	DataT,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(request: RequestT, body = {}, options: Options<DataT, RequestT> = {}) => {
	return makeRequest<DataT, ErrorT>(request, { method: 'POST', body, ...options })
}

export const usePutReq = <
	DataT,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(request: RequestT, body = {}, options: Options<DataT, RequestT> = {}) => {
	return makeRequest<DataT, ErrorT>(request, { method: 'PUT', body, ...options })
}

export const usePatchReq = <
	DataT,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(request: RequestT, body = {}, options: Options<DataT, RequestT> = {}) => {
	return makeRequest<DataT, ErrorT>(request, { method: 'PATCH', body, ...options })
}

export const useDeleteReq = <
	DataT,
	ErrorT = FetchError | null,
	RequestT extends NitroFetchRequest = NitroFetchRequest
>(request: RequestT, options: Options<DataT, RequestT> = {}) => {
	return makeRequest<DataT, ErrorT>(request, { method: 'DELETE', ...options })
}

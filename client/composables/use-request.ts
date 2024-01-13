import type { FetchError } from 'ofetch'
import type { UseFetchOptions } from 'nuxt/app'
import { makeRequest } from '~/utils/request'

export const useGetReq = <DataT, ErrorT = FetchError | null>(path: string, options = {}) => {
	return makeRequest<DataT, ErrorT>(path, { method: 'get', ...options })
}

export const usePostReq = <DataT, ErrorT = FetchError | null>(path: string, body: any = {}, options: UseFetchOptions<DataT> = {}) => {
	return makeRequest<DataT, ErrorT>(path, { method: 'POST', body, ...options })
}

export const usePutReq = <DataT, ErrorT = FetchError | null>(path: string, body: any = {}, options: UseFetchOptions<DataT> = {}) => {
	return makeRequest<DataT, ErrorT>(path, { method: 'PUT', body, ...options })
}

export const useDeleteReq = <DataT, ErrorT = FetchError | null>(path: string, options: UseFetchOptions<DataT> = {}) => {
	return makeRequest<DataT, ErrorT>(path, { method: 'DELETE', ...options })
}

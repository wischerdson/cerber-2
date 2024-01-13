import { useDeleteReq, useGetReq, usePostReq } from '~/composables/use-request'

export type User = {
	id: number,
	first_name: string,
	last_name: string,
	patronymic: string|null,
	email: string|null,
	phone: string|null,
	timezone: string|null,
	timezone_offset: number|null,
	created_at: number
}

export type AccessTokenForm = {
	login: string,
	password: string
}

export type AccessToken = {
	token: string
}

export const fetchUser = () => useGetReq<User>('/current-user')

export const createAccessToken = (form: AccessTokenForm) => usePostReq<AccessToken>('/auth/token', form)

export const invalidateAccessToken = () => useDeleteReq('/invalidate-access-token')

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

type TokensPairResponse = {
	token_type: 'Bearer'
	access_token: string
	refresh_token: string
}

type RequestDataCreateTokensPair = {
	grant_type: 'password'
	login: string
	password: string
}

type RequestDataCreateTokensPair = {
	grant_type: 'refresh_token'
	refresh_token: string
}

export const fetchUser = () => useGetReq<User>('/current-user').key('me')

export const createTokensPair = (data: RequestDataCreateTokensPair) => usePostReq<AccessToken>('/auth/token', form)

export const invalidateAccessToken = () => useDeleteReq('/invalidate-access-token')

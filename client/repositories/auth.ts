import { useDeleteReq, usePostReq } from '~/composables/use-request'

export type TokensPairIssuingResponse = {
	token_type: string
	access_token: string
	refresh_token: string
}

export type GrantType = 'password' | 'refresh_token'

export type Credentials = { [key: string]: string }

const issueTokensPair = (grantType: GrantType, credentials: Credentials) => {
	return usePostReq<TokensPairIssuingResponse>('/auth/token', {
		grant_type: grantType, ...credentials
	}).shouldEncrypt().send()
}

export const issueTokensPairViaPasswordGrant = (login: string, password: string) => {
	return issueTokensPair('password', { login, password })
}

export const issueTokensPairViaRefreshToken = (refreshToken: string) => {
	return issueTokensPair('refresh_token', { refresh_token: refreshToken })
}

export const revokeSession = () => {
	return useDeleteReq('/auth/session')
}

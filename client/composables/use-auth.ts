import type { AuthProvider, AuthType } from '~/utils/auth'
import type { User } from '~/repositories/user'
import { resolveAuthProvider } from '~/utils/auth'
import { useNuxtApp, useState } from 'nuxt/app'
import { fetchUser } from '~/repositories/user'
import { usePostReq } from './use-request'
import { isJwtExpired } from '~/utils/helpers'

type AuthTokenResponse = {
	token_type: string,
	access_token: string,
	refresh_token: string
}

export const useAuth = (key: AuthType) => {
	const { $auth } = useNuxtApp()
	return $auth(key)
}

export const useUser = async (key: AuthType) => {
	const state = useState<{ [key in AuthType]?: User | undefined }>('users', () => {
		return { [key]: undefined }
	})

	if (!state.value || !state.value[key]) {
		const { data, status } = await fetchUser().sign(key).send()

		if (status.value === 'success') {
			state.value[key] = data.value
		}
	}

	return state.value[key] as User
}

export const usePasswordGrant = (login: string, password: string, authProvider: AuthProvider | AuthType) => {
	const _authProvider = resolveAuthProvider(authProvider)

	return {
		authenticate() {
			return usePostReq<AuthTokenResponse>('/auth/token', {
				grant_type: 'password', login, password
			}).send().then((data) => _authProvider.saveSignature(data.access_token))
		}
	}
}

export const useRefreshTokenGrant = (refreshToken: string, authProvider: AuthProvider | AuthType) => {
	const _authProvider = resolveAuthProvider(authProvider)

	return {
		authenticate() {
			if (isJwtExpired(refreshToken)) {
				_authProvider.logout()

				return new Promise((_, reject) => reject())
			}

			return usePostReq<AuthTokenResponse>('/auth/token', {
				grant_type: 'refresh_token',
				refresh_token: refreshToken
			}).send()
				.then((data) => _authProvider.saveSignature(data.access_token))
				.catch(e => {
					if (e.error_reason === 'auth_credentials_error') {
						return _authProvider.logout()
					}

					throw e
				})
		}
	}
}

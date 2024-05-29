import type { AppRequest } from './request'
import type { Ref } from 'vue'
import { useNuxtApp } from 'nuxt/app'
import { isJwtExpired } from '~/utils/helpers'
import { useDeleteReq, usePostReq } from '~/composables/use-request'

export type AuthType = Parameters<ReturnType<typeof useNuxtApp>['$auth']>[0]

interface AuthProvider {
	saveTokensPair(accessToken: string, refreshToken: string): void
	signRequest(request: Omit<AppRequest, 'send'>): AppRequest
	validateSignature(): Promise<boolean>
	logout(): Promise<void>
}

export const defineAuthProvider = (storage: Ref<{ access_token: string, refresh_token: string } | null>): AuthProvider => {
	const issueTokensPair = () => {
		return usePostReq<{
			token_type: string
			access_token: string
			refresh_token: string
		}>('/auth/token', {
			grant_type: 'refresh_token',
			refresh_token: storage.value?.refresh_token
		}).send()
	}

	const revokeSession = () => signRequest(useDeleteReq('/auth/session')).send()

	const signRequest: AuthProvider['signRequest'] = request => {
		return request.setBearerToken(storage.value ? storage.value.access_token : '')
	}

	const saveTokensPair: AuthProvider['saveTokensPair'] = (accessToken, refreshToken) => {
		storage.value = { access_token: accessToken, refresh_token: refreshToken }
	}

	const validateSignature: AuthProvider['validateSignature'] = () => {
		return new Promise(resolve => {
			if (!storage.value) {
				return resolve(false)
			}

			if (isJwtExpired(storage.value.access_token)) {
				if (isJwtExpired(storage.value.refresh_token)) {
					return resolve(false)
				}

				issueTokensPair()
					.then(pair => saveTokensPair(pair.access_token, pair.refresh_token))
					.catch(reason => {
						if (reason.data && reason.data.error_reason === 'auth_credentials_error') {
							return resolve(false)
						}

						console.error('Error occured while tokens pair issuing: ', JSON.parse(JSON.stringify(reason)))

						throw reason
					})
			}

			resolve(true)
		})
	}

	const logout: AuthProvider['logout'] = async () => {
		try { await revokeSession() } catch {}

		storage.value = null
	}

	return { signRequest, saveTokensPair, validateSignature, logout }
}




// export const resolveAuthProvider = (authProvider: AuthProvider | AuthType) => {
// 	if (typeof authProvider === 'string') {
// 		const { $auth } = useNuxtApp()
// 		authProvider = $auth(authProvider)
// 	}

// 	return authProvider
// }

// export const bearerToken = (storage: Storage, onLogout?: (provider: AuthProvider) => Promise<unknown> | undefined) => {
// 	const provider: AuthProvider = {
// 		sign({ setHeader }) {
// 			const accessToken = storage.get()

// 			if (isJwtExpired(accessToken)) {

// 			}

// 			setHeader('Authorization', `Bearer ${accessToken}`)
// 		},
// 		logout(quietly = false) {
// 			let promise: Promise<unknown> | undefined

// 			if (!quietly && onLogout) {
// 				promise = onLogout(provider)
// 			}

// 			Promise.resolve(promise).then(() => storage.erase())

// 			return promise
// 		},
// 		saveTokensPair(pair) {

// 		},
// 		canSign() {
// 			return storage.has()
// 		},
// 		cannotSign() {
// 			return storage.hasNot()
// 		},
// 		saveSignature(value: string) {
// 			storage.set(value)
// 		}
// 	}

// 	return provider
// }

import type { NitroFetchRequest } from 'nitropack'
import type { AppRequest } from './request'
import type { Storage } from './storages'
import { useNuxtApp } from 'nuxt/app'
import { isJwtExpired } from '~/utils/helpers'

export type AuthType = Parameters<ReturnType<typeof useNuxtApp>['$auth']>[0]

export type TokensPair = {
	access_token: string
	refresh_token: string
}

export interface AuthProvider {
	sign(request: Omit<AppRequest<any, any, any, NitroFetchRequest>, 'send'>): void
	canSign(): boolean
	cannotSign(): boolean
	saveTokensPair(pair: TokensPair): void
	logout(quietly?: boolean): void
}

export const resolveAuthProvider = (authProvider: AuthProvider | AuthType) => {
	if (typeof authProvider === 'string') {
		const { $auth } = useNuxtApp()
		authProvider = $auth(authProvider)
	}

	return authProvider
}

export const bearerToken = (storage: Storage, onLogout?: (provider: AuthProvider) => Promise<unknown> | undefined) => {
	const provider: AuthProvider = {
		sign({ setHeader }) {
			const accessToken = storage.get()

			if (isJwtExpired(accessToken)) {

			}

			setHeader('Authorization', `Bearer ${accessToken}`)
		},
		logout(quietly = false) {
			let promise: Promise<unknown> | undefined

			if (!quietly && onLogout) {
				promise = onLogout(provider)
			}

			Promise.resolve(promise).then(() => storage.erase())

			return promise
		},
		saveTokensPair(pair) {

		},
		canSign() {
			return storage.has()
		},
		cannotSign() {
			return storage.hasNot()
		},
		saveSignature(value: string) {
			storage.set(value)
		}
	}

	return provider
}

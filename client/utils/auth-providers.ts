import type { AppRequest } from './request'
import type { Storage } from './storages'

export interface AuthProvider {
	sign(request: Omit<AppRequest<any, any>, 'send'>): void
	canSign(): boolean
	cannotSign(): boolean
	saveSignature(value: any): void
	logout(quietly?: boolean): void
}

const authProviderTemplate = (storage: Storage) => ({
	canSign() {
		return storage.has()
	},
	cannotSign() {
		return storage.hasNot()
	},
	saveSignature(value: string) {
		storage.set(value)
	}
})

export const bearerToken = (storage: Storage, onLogout?: (provider: AuthProvider) => Promise<unknown> | undefined) => {
	const provider: AuthProvider = {
		sign({ setHeader }) {
			setHeader('Authorization', `Bearer ${storage.get()}`)
		},
		logout(quietly = false) {
			let promise: Promise<unknown> | undefined

			if (!quietly && onLogout) {
				promise = onLogout(provider)
			}

			Promise.resolve(promise).then(() => storage.erase())

			return promise
		},
		...authProviderTemplate(storage)
	}

	return provider
}

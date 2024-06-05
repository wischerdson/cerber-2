import type { CookieOptions } from 'nuxt/app'
import type { DefinedStorage } from '~/utils/storages'
import { defineStorage, cookieStorageDriver, localStorageDriver, dummyStorageDriver } from '~/utils/storages'

type _CookieOptions = CookieOptions & { readonly?: false | undefined; }

/****************** Cookie storage ******************/

export function useCookieStorage<T>(key: string, init: () => T, cookieOptions?: _CookieOptions): DefinedStorage<T>
export function useCookieStorage<T>(key: string, init?: () => T, cookieOptions?: _CookieOptions): DefinedStorage<T | null>

export function useCookieStorage<T>(
	key: string,
	init?: () => T,
	cookieOptions: _CookieOptions = {}
) {
	return defineStorage(key, cookieStorageDriver(init, cookieOptions))
}

/****************** Localstorage ******************/

export function useLocalStorage<T>(key: string, init: () => T): DefinedStorage<T>
export function useLocalStorage<T>(key: string, init?: () => T): DefinedStorage<T | null>

export function useLocalStorage<T>(
	key: string,
	init?: () => T
) {
	const driver = process.server ? dummyStorageDriver(init) : localStorageDriver(init)

	return defineStorage(key, driver)
}

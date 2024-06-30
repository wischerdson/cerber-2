import type { WatchOptions } from 'vue'
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
	cookieOptions: _CookieOptions = {},
	watchOptions: WatchOptions | false = {}
) {
	return defineStorage(key, cookieStorageDriver(init, cookieOptions), watchOptions)
}

/****************** Localstorage ******************/

export function useLocalStorage<T>(key: string, init: () => T): DefinedStorage<T>
export function useLocalStorage<T>(key: string, init?: () => T): DefinedStorage<T | null>

export function useLocalStorage<T>(
	key: string,
	init?: () => T,
	watchOptions: WatchOptions | false = {}
) {
	const driver = import.meta.server ? dummyStorageDriver(init) : localStorageDriver(init)

	return defineStorage(key, driver, watchOptions)
}

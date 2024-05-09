import type { CookieOptions } from 'nuxt/app'
import type { WatchOptions, Ref } from 'vue'
import { initStorage, cookieStorageDriver, localStorageDriver, dummyStorageDriver } from '~/utils/storages'

export const useCookieStorage = <T>(
	key: string,
	init?: () => T,
	cookieOptions: CookieOptions & { readonly?: false | undefined; } = {},
	watchOptions: WatchOptions = {}
): Ref<T | null> => {
	return initStorage<T>(key, cookieStorageDriver(init, cookieOptions), watchOptions)
}

export const useLocalStorage = <T>(
	key: string,
	init?: () => T,
	watchOptions: WatchOptions = {}
): Ref<T | null> => {
	const driver = process.server ? dummyStorageDriver(init) : localStorageDriver(init)

	return initStorage<T>(key, driver, watchOptions)
}

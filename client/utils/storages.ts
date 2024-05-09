import type { CookieOptions } from 'nuxt/app'
import type { WatchOptions } from 'vue'
import { watch } from 'vue'
import { defaultsDeep, isObject, snakeCase } from 'lodash-es'
import { useCookie, useState } from 'nuxt/app'
import { singletonClientOnly } from './singleton'

interface StorageDriver<T> {
	uid: string
	read(key: string): T | null
	write(key: string, value: T | null): void
}

const stringify = (data: unknown): string => typeof data === 'object' ? JSON.stringify(data) : `${data}`

const handleFetchedData = <T>(rawData: string | null | undefined, init?: () => T): T | null => {
	if (!rawData) {
		return (init || (() => null))()
	}

	try {
		return JSON.parse(rawData) as T;
	} catch (e) {
		return rawData as T
	}
}

export const cookieStorageDriver = <T>(
	init?: () => T,
	config: CookieOptions & { readonly?: false | undefined; } = {}
): StorageDriver<T> => {
	const opts = defaultsDeep(config, {
		sameSite: 'strict',
		maxAge: 2147483647
	})

	return {
		uid: 'cookie',
		read(key: string) {
			return handleFetchedData(
				useCookie(key, opts).value,
				init
			)
		},
		write(key: string, value: T) {
			useCookie(key, opts).value = stringify(value)
		}
	}
}

export const localStorageDriver = <T>(init?: () => T): StorageDriver<T> => {
	if (process.server) {
		throw new Error('There is impossible to use localStorage driver on the server side')
	}

	return {
		uid: 'localstorage',
		read(key: string) {
			return handleFetchedData(
				window.localStorage.getItem(key),
				init
			)
		},
		write(key: string, value: T) {
			window.localStorage.setItem(key, stringify(value))
		}
	}
}

export const dummyStorageDriver = <T>(init?: () => T): StorageDriver<T> => {
	return {
		uid: 'dummy',
		read: init || (() => null),
		write: () => void 0
	}
}

export const initStorage = <T>(key: string, driver: StorageDriver<T>, watchOptions: WatchOptions = {}) => {
	const stateKey = snakeCase(`${driver.uid}_${key}`)

	return singletonClientOnly(stateKey, () => {
		const storageKey = snakeCase(`app_${key}`)

		const state = useState<T | null>(stateKey, () => driver.read(storageKey))

		if (isObject(state.value) && !('deep' in watchOptions)) {
			watchOptions.deep = true
		}

		watch(state, v => driver.write(storageKey, v), watchOptions)

		return state
	})
}

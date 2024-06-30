import type { CookieOptions } from 'nuxt/app'
import type { Ref, WatchOptions } from 'vue'
import { watch } from 'vue'
import { defaultsDeep, snakeCase } from 'lodash-es'
import { useCookie, useState } from 'nuxt/app'
import { singletonClientOnly } from './singleton'

export interface StorageDriver<T> {
	uid: string
	read(key: string): T
	write(key: string, value: T): void
}

export type DefinedStorage<T> = [state: Ref<T>, write: () => void]

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

export function cookieStorageDriver<T>(init: () => T, config?: CookieOptions & { readonly?: false | undefined; }): StorageDriver<T>
export function cookieStorageDriver<T>(init?: () => T, config?: CookieOptions & { readonly?: false | undefined; }): StorageDriver<T | null>
export function cookieStorageDriver<T>(
	init?: () => T,
	config: CookieOptions & { readonly?: false | undefined; } = {}
): StorageDriver<T | null> {
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

export function localStorageDriver<T>(init: () => T): StorageDriver<T>
export function localStorageDriver<T>(init?: () => T): StorageDriver<T | null>
export function localStorageDriver<T>(init?: () => T): StorageDriver<T | null> {
	if (import.meta.server) {
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

export function dummyStorageDriver<T>(init: () => T): StorageDriver<T>
export function dummyStorageDriver<T>(init?: () => T): StorageDriver<T | null>
export function dummyStorageDriver<T>(init?: () => T): StorageDriver<T | null> {
	return {
		uid: 'dummy',
		read: init || (() => null),
		write: () => void 0
	}
}

export const defineStorage = <T>(key: string, driver: StorageDriver<T>, watchOptions: WatchOptions | false = {}): DefinedStorage<T> => {
	const stateKey = snakeCase(`storage_${driver.uid}_${key}`)

	return singletonClientOnly(stateKey, () => {
		const storageKey = snakeCase(`app_${key}`)
		const state = useState<T>(stateKey, () => driver.read(storageKey))

		const write = () => driver.write(storageKey, state.value)

		watchOptions !== false && watch(state, write, watchOptions)

		return [ state, write ]
	})
}

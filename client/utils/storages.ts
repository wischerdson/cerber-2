import type { CookieOptions } from 'nuxt/app'
import { defaultsDeep } from 'lodash'
import { useCookie } from 'nuxt/app'

export interface Storage {
	set<T extends string | undefined>(value: T): T
	get(): string
	has(): boolean
	hasNot(): boolean
	erase(): void
}

export const cookie = (name: string, config: CookieOptions = {}): Storage => {
	const defaultConfig: CookieOptions = {
		sameSite: 'strict',
		maxAge: 2147483647
	}

	config = defaultsDeep(config, defaultConfig)

	const cookie = useCookie(name, config)

	const storage: Storage = {
		set(value) {
			cookie.value = value
			return value
		},
		get() {
			return cookie.value
		},
		has() {
			return !!cookie.value
		},
		hasNot() {
			return !cookie.value
		},
		erase() {
			storage.set(void 0)
		}
	}

	return storage
}

const instances: any = {}

export const singleton = <T = any>(key: string, callback: () => T): T => {
	if (!(key in instances)) {
		instances[key] = callback()
	}

	return instances[key]
}

export const singletonClientOnly = <T = any>(key: string, callback: () => T): T => {
	if (import.meta.server) {
		return callback()
	}

	return singleton(key, callback)
}

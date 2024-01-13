import { trim } from 'lodash'
import { useRoute, useRouter, useRuntimeConfig } from '#imports'
import type { RouteRecordNormalized } from 'vue-router'

export const storageUrl = (path: string) => {
	return trim(useRuntimeConfig().public.storageBaseUrl, '/') + '/' + trim(path, '/')
}

export const apiBaseUrl = () => {
	const config = useRuntimeConfig()
	return process.server ? config.apiBaseUrl : config.public.apiBaseUrl
}

export const replacePageView = (targetRoute: RouteRecordNormalized) => {
	const router = useRouter()
	const currentRoute = router.currentRoute.value

	router.addRoute({ ...targetRoute, path: currentRoute.path, name: currentRoute.name || undefined })
	const promise = router.replace(currentRoute)
	router.addRoute(currentRoute.matched[0])

	return promise
}

export const reloadPageView = () => useRouter().replace(useRoute())

export const forEachObjectDeep = function* (object: { [key: string]: any }) {
	const stack = [object]

	while (stack.length) {
		const value = stack.pop()

		if (typeof value === 'object') {
			Object.values(value).forEach(v => stack.push(v))
		} else {
			yield value
		}
	}
}

export const uid = () => Date.now().toString(36) + Math.random().toString(36).substring(2)
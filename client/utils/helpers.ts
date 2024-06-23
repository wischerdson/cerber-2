import { trim } from 'lodash-es'
import { Buffer } from 'buffer'
import { useRouter, useRuntimeConfig } from '#imports'

export const storageUrl = (path: string) => {
	return trim(useRuntimeConfig().public.storageBaseUrl, '/') + '/' + trim(path, '/')
}

export const apiBaseUrl = () => {
	const config = useRuntimeConfig()
	return process.server ? config.apiBaseUrl : config.public.apiBaseUrl
}

export const replaceView = (routeName: string) => {
	const router = useRouter()
	const targetRoute = router.getRoutes().find(route => route.name === routeName)
	const currentRoute = router.currentRoute.value.matched[0]

	if (targetRoute) {
		router.addRoute({
			...currentRoute,
			components: targetRoute.components,
			meta: targetRoute.meta
		})

		const promise = router.replace({ force: true })
		router.addRoute(currentRoute)

		return promise
	}
}

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

export const parseJwt = <T>(jwt: string): T => {
	const base64Url = jwt.split('.')[1]
	const json = Buffer.from(base64Url, 'base64').toString()

	return JSON.parse(json)
}

export const isJwtExpired = (jwt: string): boolean => {
	const payload = parseJwt<{ exp: number }>(jwt)

	if (payload.hasOwnProperty('exp')) {
		const expiration = payload.exp

		return new Date().getTime() > new Date(expiration*1000).getTime()
	}

	return false
}

export const isUrl = (string: string): boolean => {
	const httpSchemeRegExp = /^https?:\/\//i
	const domainRegExp = /^(?!-)[A-z0-9-]+([\-\.]{1}[a-z0-9]+)*\.[A-z]{2,6}($|\/)/i
	const ipv4RegExp = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}($|\/)/i

	return httpSchemeRegExp.test(string) ||
		domainRegExp.test(string) ||
		ipv4RegExp.test(string)
}

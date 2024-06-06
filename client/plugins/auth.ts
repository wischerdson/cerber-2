import type { AuthProvider } from '~/utils/auth'
import { defineNuxtPlugin } from 'nuxt/app'
import { defineJwtPairAuthProvider } from '~/utils/auth'
import AuthMiddleware from '~/middleware/auth'
import { useCookieStorage, useLocalStorage, useRouter } from '#imports'

export default defineNuxtPlugin(() => {
	const router = useRouter()
	const [ pair, _, watchStorage ] = useLocalStorage<{ access_token: string, refresh_token: string }>('user_auth')
	const [ authenticated, __, watchIsAuthenticated ] = useCookieStorage('is_authenticated')

	watchStorage()
	watchIsAuthenticated()

	const defaultUser = defineJwtPairAuthProvider(pair, () => {
		useRouter().replace({ force: true })

		authenticated.value = false
	})

	process.client && (authenticated.value = defaultUser.canSign())

	const providers = { default: defaultUser }

	const resolveAuthProvider = (provider: keyof typeof providers | AuthProvider) => {
		if (typeof provider === 'string') {
			return providers[provider]
		}

		return provider
	}

	return {
		provide: { resolveAuthProvider }
	}
})

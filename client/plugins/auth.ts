import type { AuthProvider } from '~/utils/auth'
import { defineNuxtPlugin } from 'nuxt/app'
import { defineJwtPairAuthProvider } from '~/utils/auth'
import AuthMiddleware from '~/middleware/auth'
import { useLocalStorage, useRouter } from '#imports'

export default defineNuxtPlugin(() => {
	const router = useRouter()
	const [ pair, write, watchStorage ] = useLocalStorage<{ access_token: string, refresh_token: string }>('user_auth')

	watchStorage()

	const defaultUser = defineJwtPairAuthProvider(pair, () => {
		AuthMiddleware(router.currentRoute.value, router.currentRoute.value)
	})

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

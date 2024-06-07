import type { AuthProvider } from '~/utils/auth'
import { defineNuxtPlugin } from 'nuxt/app'
import { defineJwtPairAuthProvider, type JwtTokensPair } from '~/utils/auth'
import { useCookieStorage, useLocalStorage, useRouter } from '#imports'

const defineDefaultUserAuthProvider = () => {
	/**
	 * Так как пару токенов для данного провайдера решено хранить в клиентском localstorage
	 * (чтоб не гонять их в куках по сети и свести вероятность перехвата к минимуму), следовательно
	 * сервер, который рендерит страницу, не шарит, что пользователь авторизирован. Поэтому в куках
	 * серваку мы поясняем, что у клиента есть данные авторизации, только он их ему не отдаст.
	 * Сервер рендерит не страницу авторизации а пустой личный кабинет, а далее клиент будет сам
	 * запрашивать данные по API и дорисовывать интерфейс (если у него действительно валидная авторизация)
	 */

	const router = useRouter()
	const [ authenticated ] = useCookieStorage<boolean>('default_authenticated', () => false)
	const [ pair ] = useLocalStorage<JwtTokensPair | undefined>('user_auth', () => {
		if (process.server && authenticated.value) {
			return { access_token: 'valid', refresh_token: 'valid' }
		}
	})

	return defineJwtPairAuthProvider(pair, () => {
		authenticated.value = true
	}, () => {
		authenticated.value = false
		router.replace({ force: true })
	})
}

export default defineNuxtPlugin(() => {
	const providers = {
		default: defineDefaultUserAuthProvider()
	}

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

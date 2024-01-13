import { defineNuxtPlugin } from 'nuxt/app'
import { bearerToken } from '~/utils/auth-providers'
import { cookie } from '~/utils/storages'
import { invalidateAccessToken } from '~/repositories/user'
import { useAuthSignInPageView } from '~/composables/use-page-view'

export default defineNuxtPlugin(() => {
	const user = bearerToken(cookie('access-token'), auth => {
		return Promise.all([
			useAuthSignInPageView(),
			invalidateAccessToken().sign(auth).send()
		])
	})

	const auth = { user }

	return {
		provide: {
			auth: (key: keyof typeof auth) => auth[key]
		}
	}
})
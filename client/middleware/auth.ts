import { defineNuxtRouteMiddleware, useNuxtApp } from 'nuxt/app'
import { useNoindexHeader } from '~/composables/use-noindex-header'
import { useUser } from '~/composables/use-auth'
import { useAuthSignInPageView } from '~/composables/use-page-view'

export default defineNuxtRouteMiddleware(async (to) => {
	useNoindexHeader()

	to.meta.layout = to.meta.layout || 'account'

	const { $auth } = useNuxtApp()
	const auth = $auth('user')

	return

	if (auth.cannotSign()) {
		return await useAuthSignInPageView()
	}

	const user = await useUser('user')

	if (!user) {
		return await auth.logout()
	}
})

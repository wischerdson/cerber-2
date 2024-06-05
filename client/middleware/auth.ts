import { useRouter } from '#imports'
import { useAuth } from '~/composables/use-auth'
import { defineNuxtRouteMiddleware } from 'nuxt/app'
import { useNoindexHeader } from '~/composables/use-noindex-header'
import { useAuthSignInPageView } from '~/composables/use-page-view'

export default defineNuxtRouteMiddleware(async (to) => {
	useNoindexHeader()

	to.meta.layout = to.meta.layout || 'account'

	const { canSign } = useAuth('default')

	if (!canSign()) {
		const router = useRouter()

		const currentRoute = router.currentRoute.value
		// const targetRoute = router.resolve('/auth/sign-in')

		const targetRoute = router.getRoutes().find(route => route.path === '/auth/sign-in')


		if (targetRoute) {
			// router.removeRoute(currentRoute.name as string)

			console.log({ ...targetRoute, path: currentRoute.path, name: currentRoute.name as string })
			router.addRoute({ ...targetRoute, path: currentRoute.path, name: currentRoute.name as string })// ...targetRoute, path: currentRoute.path })
			// const promise = router.replace(currentRoute)
			// router.addRoute(currentRoute.matched[0])

			// return promise

		}



		// return router.replace(currentRoute)

		// console.log(targetRoute)

		// targetRoute.path = currentRoute.path
		// targetRoute.fullPath = currentRoute.fullPath

		// targetRoute.

		// router.addRoute(route)


		// router.replace(targetRoute)

		// console.log(route, router.currentRoute.value)

		// useRouter().addRoute(router.currentRoute.value, )
		// return useAuthSignInPageView()
	}

	// return to
})

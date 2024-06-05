import { replacePageView } from '~/utils/helpers'
import { useRouter } from 'nuxt/app'

export const useAuthSignInPageView = () => {
	const SIGN_IN_ROUTE = 'auth-test'
	const loginRoute = useRouter().getRoutes().find(route => route.name === SIGN_IN_ROUTE)

	return Promise.resolve(loginRoute && replacePageView(loginRoute))
}

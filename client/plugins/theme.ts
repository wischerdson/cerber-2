import { defineNuxtPlugin } from 'nuxt/app'
import { useTheme } from '~/composables/use-theme'
import { useCookieStorage } from '~/composables/use-storage'

export default defineNuxtPlugin(async () => {
	const [state] = useCookieStorage(
		'color-mode',
		() => ({ mode: 'system', isDark: true }),
		{ watch: { deep: true } }
	)

	const theme = useTheme(state)

	return {
		provide: { theme }
	}
})

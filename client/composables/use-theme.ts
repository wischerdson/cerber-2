import { useCookie, useState, watch } from "#imports"
import { singletonClientOnly } from '~/utils/singleton'

export type ColorMode = 'system' | 'light' | 'dark'

export interface Theme {
	mode: ColorMode
	isDark: boolean
}

export const useTheme = () => singletonClientOnly('theme', () => {
	const STORAGE_KEY = 'app-color-mode'

	const theme = useState<Theme>('theme', () => ({ mode: 'system', isDark: true }))

	const cookie = useCookie(STORAGE_KEY, {
		default: () => theme.value,
		maxAge: 60 * 60 * 24 * 365,
		sameSite: 'strict'
	})

	theme.value = cookie.value

	const darkModePreference = process.client ? window.matchMedia('(prefers-color-scheme: dark)') : null

	const isDark = () => {
		if (theme.value.mode === 'system') {
			if (darkModePreference) {
				return darkModePreference.matches
			}

			return theme.value.isDark
		}

		return theme.value.mode === 'dark'
	}

	const update = () => {
		const _isDark = isDark()

		if (theme.value.isDark !== _isDark) {
			document.documentElement.classList.add('theme-changing')
		}

		theme.value.isDark = _isDark

		cookie.value = theme.value

		if (process.server) {
			return
		}

		if (_isDark) {
			document.documentElement.classList.add('dark')
		} else {
			document.documentElement.classList.remove('dark')
		}
	}

	const themeChangingTransitionEnd = () => {
		document.documentElement.classList.remove('theme-changing')
	}

	const stopWatch = watch(theme, update, { immediate: true, deep: true })

	darkModePreference?.addEventListener('change', update)

	if (document) {
		document?.body.addEventListener('transitionend', themeChangingTransitionEnd)
	}

	const destroy = () => {
		stopWatch()
		darkModePreference?.removeEventListener('change', update)
		document?.body.removeEventListener('transitionend', themeChangingTransitionEnd)
	}

	return { theme, destroy }
})

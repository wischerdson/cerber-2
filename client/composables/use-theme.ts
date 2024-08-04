import type { Ref } from 'vue'
import { computed, watch } from 'vue'
import { singletonClientOnly } from '~/utils/singleton'

export type ThemeMode = 'system' | 'light' | 'dark'

export type ColorScheme = 'light' | 'dark'

export interface Theme {
	mode: ThemeMode
	scheme: ColorScheme
}

export const useTheme = (state: Ref<Theme>) => singletonClientOnly('theme', () => {
	const darkModePreference = import.meta.client ? window.matchMedia('(prefers-color-scheme: dark)') : null

	const resolveScheme = (mode: ThemeMode) => {
		if (mode === 'system') {
			return darkModePreference && darkModePreference.matches ? 'dark' : 'light'
		}

		return mode
	}

	const refreshStateScheme = () => state.value.scheme = resolveScheme(state.value.mode)

	const setMode = (mode: ThemeMode) => {
		state.value.mode = mode
		refreshStateScheme()
	}

	const scheme = computed(() => state.value.scheme)

	watch(() => state.value.scheme, () => {
		const oldScheme = state.value.scheme

		if (import.meta.server || state.value.scheme == oldScheme) {
			return
		}

		document.documentElement.classList.add('theme-changing')
	})

	const themeChangingTransitionEnd = () => {
		document.documentElement.classList.remove('theme-changing')
	}

	if (import.meta.client) {
		setMode(state.value.mode)

		darkModePreference?.addEventListener('change', () => {
			if (state.value.mode === 'system') {
				refreshStateScheme()
			}
		})
	}

	document.body.addEventListener('transitionend', themeChangingTransitionEnd)




	// const update = () => {
	// 	const _isDark = isDark()

	// 	if (state.value.isDark !== _isDark) {
	// 		document?.documentElement.classList.add('theme-changing')
	// 	}

	// 	state.value.isDark = _isDark

	// 	if (import.meta.server) {
	// 		return
	// 	}

	// 	if (_isDark) {
	// 		document.documentElement.classList.add('dark')
	// 	} else {
	// 		document.documentElement.classList.remove('dark')
	// 	}
	// }



	// const stopWatch = watch(state, update, { immediate: true, deep: true })

	// darkModePreference?.addEventListener('change', update)
	// document?.body.addEventListener('transitionend', themeChangingTransitionEnd)

	// const destroy = () => {
	// 	stopWatch()
	// 	darkModePreference?.removeEventListener('change', update)
	// 	document?.body.removeEventListener('transitionend', themeChangingTransitionEnd)
	// }

	// state.value.isDark = isDark()

	return { theme: state }
})

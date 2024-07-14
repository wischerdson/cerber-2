import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useSecretsStore = defineStore('secrets', () => {
	const mode = ref<'create' | 'view' | null>(null)

	const setModeCreate = () => mode.value = 'create'

	const setModeView = () => mode.value = 'view'

	const unsetMode = () => mode.value = null

	return {
		mode: computed(() => mode),
		setModeCreate, setModeView, unsetMode
	}
})

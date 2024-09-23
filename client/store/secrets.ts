import type { Secret } from '~/repositories/adapters/secret-adapter'
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchSecrets } from '~/repositories/secrets'

export const useSecretsStore = defineStore('secrets', () => {
	const mode = ref<'create' | 'view' | null>(null)
	const secrets = ref<Secret[]>([])
	const secretForView = ref<Secret>()

	const setModeCreate = () => mode.value = 'create'

	const resetMode = () => mode.value = null

	const viewSecret = (secret: Secret) => {
		secretForView.value = secret
		mode.value = 'view'
	}

	const unsetMode = () => mode.value = null

	const fetch = async () => {
		const data = await fetchSecrets()

		secrets.value = data
	}

	return {
		mode: computed(() => mode.value),
		secrets: computed(() => secrets.value),
		secretForView: computed(() => secretForView.value),
		setModeCreate, viewSecret, resetMode, unsetMode, fetch
	}
})

import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { uid } from '~/utils/helpers'
import { fetchSecrets, type ClientSecret, type ClientSecretField } from '~/repositories/secrets'

export type Secret = ClientSecret & { clientCode: string }
export type SecretField = ClientSecretField & { clientCode: string }

export const useSecretsStore = defineStore('secrets', () => {
	const mode = ref<'create' | 'view' | null>(null)
	const secrets = ref<Secret[]>([])
	const secretForView = ref<Secret>()

	const setModeCreate = () => mode.value = 'create'

	const viewSecret = (secret: Secret) => {
		secretForView.value = secret
		mode.value = 'view'
	}

	const unsetMode = () => mode.value = null

	const fetch = async () => {
		const data = await fetchSecrets()

		if (data) {
			secrets.value = data.map(s => {
				(s as Secret).clientCode = uid()

				return s
			}) as Secret[]
		}
	}

	return {
		mode: computed(() => mode.value),
		secrets: computed(() => secrets.value),
		secretForView: computed(() => secretForView.value),
		setModeCreate, viewSecret, unsetMode, fetch
	}
})

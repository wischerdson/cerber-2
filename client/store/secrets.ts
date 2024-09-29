import type { Secret, SecretPreview } from '~/repositories/adapters/secret-adapter'
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchSecretDetails, fetchSecrets } from '~/repositories/secrets'

export const useSecretsStore = defineStore('secrets', () => {
	const mode = ref<'create' | 'view' | null>(null)
	const secrets = ref<SecretPreview[]>([])
	const secretsDetails = ref<Secret[]>([])
	const secretForView = ref<Secret>()

	const setModeCreate = () => mode.value = 'create'

	const resetMode = () => mode.value = null

	const viewSecretDetails = async (clientCode: string) => {
		let secret = secretsDetails.value.find(s => s.clientCode === clientCode)

		if (secret) {
			return secretForView.value = secret
		}

		const secretForLoad = secrets.value.find(s => s.clientCode === clientCode)

		if (!secretForLoad) {
			throw new Error(`Unable to find secret by code ${clientCode}`)
		}

		secret = await fetchSecretDetails(secretForLoad.id)
		secret.clientCode = clientCode

		secretsDetails.value.length >= 50 && secretsDetails.value.shift()
		secretsDetails.value.push(secret)

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
		setModeCreate, viewSecretDetails, resetMode, unsetMode, fetch
	}
})

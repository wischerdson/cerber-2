import type { AuthType } from '~/utils/request'
import type { User } from '~/repositories/user'
import { useNuxtApp, useState } from 'nuxt/app'
import { fetchUser } from '~/repositories/user'

export const useAuth = (key: AuthType) => {
	const { $auth } = useNuxtApp()
	return $auth(key)
}

export const useUser = async (key: AuthType) => {
	const state = useState<{ [key in AuthType]?: User | undefined }>('users', () => {
		return { [key]: undefined }
	})

	if (!state.value || !state.value[key]) {
		const { data, status } = await fetchUser().sign(key).send()

		if (status.value === 'success') {
			state.value[key] = data.value
		}
	}

	return state.value[key] as User
}

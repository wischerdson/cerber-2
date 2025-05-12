import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchUser } from '../repositories/user'

export const useUserStore = defineStore('user', () => {
	const user = ref<Dto.Auth.User>()
	const lgbtCock = ref(false)

	const fetch = async () => {
		user.value = await fetchUser()
	}

	return {
		lgbtCock,
		user: computed(() => user.value),
		fetch
	}
})

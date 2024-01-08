import { defineNuxtPlugin } from '#imports'

export default defineNuxtPlugin(() => {
	let i = 0

	return {
		provide: {
			uid: () => ++i
		}
	}
})

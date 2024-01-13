import { defineNuxtPlugin } from '#imports'
import { uid } from '~/utils/helpers'

export default defineNuxtPlugin(({ vueApp }) => {
	vueApp.directive('uid', {
		created: el => el.setAttribute('id', el.id || uid()),
		getSSRProps: () => ({ id: uid() })
	})
})

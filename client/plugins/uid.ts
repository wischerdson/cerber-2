import { defineNuxtPlugin } from '#imports'
import { uid } from '~/utils/helpers'

export default defineNuxtPlugin(({ vueApp }) => {
	vueApp.directive('uid', {
		created: (el, binding) => {
			const id = el.id || uid()
			el.setAttribute('id', id)

			binding.value && binding.value(id)
		},
		getSSRProps: (_, vnode) => ({ id: uid() })
	})
})

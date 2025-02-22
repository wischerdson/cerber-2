<template>
	<component
		class="ui-clickable"
		:is="nuxtLink ? NuxtLink : tag"
		@mouseup="opacityTransition = true"
		@transitionend="opacityTransition = false"
		:style="{ transition: opacityTransition ? `opacity ${duration}ms ease` : null }"
		v-bind="nuxtLink"
	>
		<slot></slot>
	</component>
</template>

<script setup lang="ts">

import type { NuxtLinkProps } from '#app'
import { ref } from '#imports'
import { NuxtLink } from '#components'

export interface UiClickableProps {
	tag?: string
	duration?: number
	nuxtLink?: NuxtLinkProps
}

withDefaults(defineProps<UiClickableProps>(), {
	tag: 'button',
	duration: 200
})

const opacityTransition = ref(false)

</script>

<style>

@layer components {
	.ui-clickable {
		cursor: pointer;
		user-select: none;

		&:active {
			opacity: .7 !important;
			transition: none !important;
		}
	}
}

</style>

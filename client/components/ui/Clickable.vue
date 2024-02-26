<template>
	<component
		class="clickable"
		:is="tag"
		@mouseup="opacityTransition = true"
		@transitionend="opacityTransition = false"
		:style="{ transition: opacityTransition ? `opacity ${duration}ms ease` : null }"
	>
		<slot></slot>
	</component>
</template>

<script setup lang="ts">

import { ref } from '#imports'

withDefaults(defineProps<{
	tag?: string
	duration?: number
}>(), {
	tag: 'button',
	duration: 200
})

const opacityTransition = ref(false)

</script>

<style lang="scss">

.clickable {
	cursor: pointer;
	user-select: none;

	&:active {
		opacity: .7 !important;
		transition: none !important;
	}
}

</style>

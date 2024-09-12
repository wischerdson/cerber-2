<template>
	<component
		class="btn"
		:class="[
			{
				'btn-custom-sizing': customSizing,
				'btn-square': square,
				'btn-rounded': rounded,
			},
			color ? `btn-${color}`: ''
		]"
		:is="tag"
		@mouseover="changeOpacityTransition"
		@mouseout="changeOpacityTransition"
		:style="{ transition: opacityTransition ? 'opacity .15s ease, background-color .15s ease' : '' }"
	>
		<div :style="{ opacity: +!loading }">
			<slot></slot>
		</div>
		<div class="absolute inset-0 flex items-center justify-center" v-if="loading">
			<icon name="svg-spinners:90-ring-with-bg" :size="`${loadingSpinnerSize}px`" />
		</div>
	</component>
</template>

<script setup lang="ts">

import { ref } from '#imports'

interface Props {
	tag?: string
	customSizing?: boolean
	loading?: boolean
	loadingSpinnerSize?: number
	color?: string
	square?: boolean
	rounded?: boolean
}

withDefaults(defineProps<Props>(), {
	tag: 'button',
	type: 'button',
	loadingSpinnerSize: 28
})

const opacityTransition = ref(false)

const changeOpacityTransition = (e: Event) => {
	opacityTransition.value = true
	setTimeout(() => opacityTransition.value = false, 150)
}

</script>

<style lang="scss">

.btn {
	display: inline-flex;
	justify-content: center;
	align-items: center;
	text-align: center;
	text-decoration: none;
	vertical-align: middle;
	cursor: pointer;
	user-select: none;
	border-radius: 8px;
	font-weight: 400;
	transition: opacity .5s ease;
	position: relative;

	&:active {
		opacity: .65;
		transition: none;
	}
}

.btn:not(.btn-custom-sizing) {
	height: 46px;
	padding: 0 16px;
}

.btn-square:not(.btn-custom-sizing) {
	width: 46px;
	padding: 0;
}

.btn-primary {
	background-color: theme('colors.gray.100');
}

html.dark .btn-primary {
	background-color: theme('colors.dark.gray-3');
}

.btn-rounded {
	border-radius: 9999px;
}

</style>

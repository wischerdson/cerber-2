<template>
	<div class="www" ref="$root" :style="{ transition }">
		<div ref="$slotWrapper">
			<div ref="$slot">
				<slot></slot>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import { ref, onMounted, onUnmounted, computed, watch } from '#imports'

const props = withDefaults(defineProps<{
	show: boolean
	transitionDuration?: number
	transitionDelay?: number
	transitionTimingFunction?: string
}>(), {
	transitionDuration: 2500,
	transitionDelay: 0,
	transitionTimingFunction: 'ease',
})

const $slotWrapper = ref<HTMLElement>()
const $slot = ref<HTMLElement>()
const $root = ref<HTMLElement>()
let observer: ResizeObserver

const transition = computed(() => `max-width ${props.transitionDuration}ms ${props.transitionTimingFunction} ${props.transitionDelay}ms`)

const show = () => {
	if (!$root.value || !$slot.value) {
		return
	}

	console.log($root.value.scrollWidth)

	$root.value.style.maxWidth = $root.value.scrollWidth + 'px'
	$slot.value.style.width = $root.value.scrollWidth + 'px'
}

const hide = () => {
	if (!$root.value) {
		return
	}

	$root.value.style.maxWidth = '0'
}

watch(() => props.show, v => v ? show() : hide(), { immediate: true })

// const widthChanged = () => {
// 	if (!$root.value || !$slotWrapper.value || !$slot.value) {
// 		return
// 	}

// 	// console.log($slot.value.scrollWidth)

// 	$slotWrapper.value.style.maxWidth = $root.value.scrollWidth + 'px'
// 	$slot.value.style.width = $root.value.scrollWidth + 'px'
// }

// onMounted(() => {
// 	observer = new ResizeObserver(widthChanged)
// 	observer.observe($slot.value as HTMLElement)
// })

// onUnmounted(() => observer.disconnect())

</script>

<style scoped lang="scss">

// .www {
// 	max-width: 0;
// 	width: 100000px;
// }

</style>

<template>
	<div class="form-group">
		<label :for="id">
			<slot name="label"></slot>
		</label>
		<textarea
			class="form-control"
			:id="id"
			ref="$textarea"
			v-bind="attrs"
		></textarea>
	</div>
</template>

<script setup lang="ts">

import { useAttrs, onMounted, onUnmounted, ref, computed, useNuxtApp } from '#imports'

const attrs = useAttrs()

const props = withDefaults(defineProps<{
	modelValue?: string
	label?: string | null
	id?: string
	autoHeight?: boolean
	allowShrink?: boolean
}>(), {
	autoHeight: true,
	allowShrink: false
})

const uid = useNuxtApp().$uid()
const id = computed(() => props.id || `textField_${uid}`)

const $textarea = ref<HTMLElement>()

const setHeight = () => {
	const tx = ($textarea.value as HTMLElement)

	if (tx.scrollHeight > tx.offsetHeight || props.allowShrink) {
		tx.style.height = '0'
		tx.style.height = `${tx.scrollHeight + 2}px`
	}
}

onMounted(() => {
	$textarea.value?.addEventListener('input', setHeight)
})

onUnmounted(() => {
	$textarea.value?.removeEventListener('input', setHeight)
})

</script>

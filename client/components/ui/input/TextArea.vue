<template>
	<slot name="label" :id="$textarea?.id"></slot>
	<textarea
		class="form-control"
		:class="`size-${size}`"
		:style="{ resize: allowShrink ? 'none' : void 0 }"
		v-uid
		v-bind="useAttrs()"
		autocomplete="off"
		:rows="rows"
		ref="$textarea"
		v-model="model"
	></textarea>
</template>

<script setup lang="ts">

import type { TextareaHTMLAttributes } from 'vue'
import { ref, onMounted, useAttrs } from '#imports'

interface Props extends /* @vue-ignore */ TextareaHTMLAttributes {
	size?: 'base' | 'lg'
	autoHeight?: boolean
	allowShrink?: boolean
	rows?: number
	value?: string|number
}

const props = withDefaults(defineProps<Props>(), {
	validationTouchEvent: 'change',
	disableLabel: false,
	size: 'base',
	autoHeight: true,
	allowShrink: false,
	rows: 3
})

const $textarea = ref<HTMLElement>()

const setHeight = () => {
	if (!$textarea.value) {
		return
	}

	const tx = ($textarea.value as HTMLElement)

	if (tx.scrollHeight > tx.offsetHeight || props.allowShrink) {
		tx.style.height = '0'
		tx.style.height = `${tx.scrollHeight + 2}px`
	}
}

const [model] = defineModel<string | number | null | undefined>({
	set: value => {
		setHeight()

		return value
	}
})

if (props.value) {
	model.value = props.value
}

onMounted(() => setHeight())

</script>

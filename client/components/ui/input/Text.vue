<template>
	<slot name="label" :id="$input?.id"></slot>
	<input
		v-uid
		class="form-control size-base"
		type="text"
		autocomplete="off"
		v-model="model"
		v-bind="useAttrs()"
		ref="$input"
	>
</template>

<script setup lang="ts">

import { ref, useAttrs } from '#imports'

const props = defineProps<{ value?: string|number }>()

const $input = ref<HTMLElement>()

const [model] = defineModel()

if (props.value) {
	model.value = props.value
}

</script>

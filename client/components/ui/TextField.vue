<template>
	<div class="form-group">
		<label class="form-label" :for="id">
			<slot name="label"></slot>
		</label>
		<input
			class="form-control"
			type="text"
			:id="id"
			autocomplete="off"
			v-bind="attrs"
			:value="modelValue"
			@input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
		>
	</div>
</template>

<script setup lang="ts">

import { useAttrs, computed, useNuxtApp } from '#imports'

const attrs = useAttrs()

defineOptions({ inheritAttrs: false })

const props = defineProps<{
	modelValue?: string
	label?: string | null
	id?: string
}>()

const emit = defineEmits<{ (e: 'update:modelValue', value: string): void }>()

const uid = useNuxtApp().$uid()
const id = computed(() => props.id || `text_field_${uid}`)

</script>

<style lang="scss">

.form-label {
	font-size: .875rem;
	margin-bottom: 6px;
	display: block;
	color: #818181;
}

.form-control {
	background-color: theme('colors.dark.gray-3');
	height: 46px;
	padding: 0 16px;
	border: 1px solid rgba(#000, 0);
	border-radius: 8px;
	display: block;
	width: 100%;
	font-size: 1rem;
}

</style>

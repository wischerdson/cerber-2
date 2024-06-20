<template>
	<InputText
		v-model="model"
		type="tel"
	/>
</template>

<script setup lang="ts">

import InputText from './Text.vue'
import { AsYouType, formatIncompletePhoneNumber } from 'libphonenumber-js'

const emit = defineEmits<{ (e: 'update:modelValue', value: string): void }>()

const asYouType = new AsYouType('RU')

const [model] = defineModel<string>({
	set(value) {
		asYouType.reset()

		asYouType.input(value)

		return asYouType.getNumber() ? asYouType.getNumber()?.number : value
	},
	get(value) {
		return formatIncompletePhoneNumber(value)
	}
})

</script>

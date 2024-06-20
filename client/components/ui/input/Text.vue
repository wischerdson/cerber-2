<template>
	<input
		v-uid="cb"
		class="form-control size-base"
		type="text"
		autocomplete="off"
		v-model="model"
	>
</template>

<script setup lang="ts">

import type { Ref } from '#imports'
import { inject, ref } from '#imports'

const props = defineProps<{ value?: string|number }>()
const uid = inject<Ref<string>>('uid', ref(''))

const cb = (id: string) => {
	if (uid) {
		uid.value = id
	}
}

const [model] = defineModel()

if (props.value) {
	model.value = props.value
}

</script>

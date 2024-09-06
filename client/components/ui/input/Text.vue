<template>
	<slot name="label" :id="id"></slot>
	<input
		:id="id"
		class="form-control size-base"
		type="text"
		autocomplete="off"
		v-model="model"
		v-bind="useAttrs()"
	>
</template>

<script setup lang="ts">

import { useAttrs, useId } from '#imports'

const id = useId()
const model = defineModel<string>()

</script>

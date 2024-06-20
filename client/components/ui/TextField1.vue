<template>
	<div>
		<label :for="uid">Some label</label>
		<InputText />
		<span>UID = {{ uid }}</span>
	</div>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import { ref, provide } from '#imports'

const uid = ref<string>()

provide('uid', uid)

</script>

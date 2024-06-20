<template>
	<input
		class="form-control size-base"
		type="text"
		v-uid="cb"
		autocomplete="off"
	>
</template>

<script setup lang="ts">

import type { Ref } from '#imports'
import { inject } from '#imports'

const uid = inject<Ref<string>>('uid')

const cb = (id: string) => {
	if (uid) {
		uid.value = id
	}
}

</script>

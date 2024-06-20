<template>
	<div>
		<label class="form-label" :for="uid">
			<slot name="label"></slot>
		</label>
		<slot name="input"></slot>
	</div>
</template>

<script setup lang="ts">

import { ref, provide, getCurrentInstance } from '#imports'

const uid = ref<string>()

const i = getCurrentInstance()


// console.log(i?.slots.input()[0].ctx)

// i?.slots.input()[0].ctx.provides['uid'] = uid

provide('uid', uid)

</script>

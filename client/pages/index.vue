<template>
	<div class="hello">
		<div>Hello</div>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

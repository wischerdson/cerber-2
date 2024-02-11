<template>
	<div class="text-3xl font-mono">
		Hello world
	</div>
</template>

<script setup lang="ts">

import { definePageMeta } from '#imports'

definePageMeta({ middleware: 'auth' })

</script>

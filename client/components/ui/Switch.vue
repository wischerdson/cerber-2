<template>
	<div
		class="ui-switch rounded-full w-10 h-6 bg-gray-150 flex items-center p-0.5"
		:class="{ on: isOn }"
		@click="isOn = !isOn"
	>
		<div class="handle w-5 h-5 bg-white rounded-full"></div>
	</div>
</template>

<script setup lang="ts">

import { ref } from 'vue'

const isOn = ref(false)

</script>

<style scoped lang="scss">

.ui-switch {
	transition: background-color .15s ease;

	.handle {
		transition: transform .15s ease;
	}

	&.on {
		background-color: theme('colors.black');

		.handle {
			transform: translateX(16px);
		}
	}
}

</style>

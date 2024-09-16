<template>
	<div class="spaces-manager">
		<SpacesSwitch class="pointer-events-auto" />
	</div>
</template>

<script setup lang="ts">

import SpacesSwitch from '~/components/account/TopBar/SpacesSwitch.vue'

</script>

<style lang="scss" scoped>

.spaces-manager {
	border-radius: 9999px;
	background-color: theme('colors.gray.50');
	position: relative;
}

html.dark {
	.spaces-manager {
		background: linear-gradient(145deg, rgba(#fff, .0875), rgba(#fff, 0.05), rgba(#fff, 0.05));
	}
}

</style>

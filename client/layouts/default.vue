<template>
	<div>
		<ScreenSize />
		<slot></slot>
	</div>
</template>

<script setup lang="ts">

import ScreenSize from '~/components/dev/ScreenSize.vue'

</script>

<style src="~/assets/sass/public/layout.scss"></style>

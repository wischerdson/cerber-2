<template>
	<div class="content-tile" :style="{ 'border-radius': `${borderRadius}px` }">
		<slot></slot>
	</div>
</template>

<script setup lang="ts">

withDefaults(defineProps<{ borderRadius?: number }>(), {
	borderRadius: 16
})

</script>

<style scoped lang="scss">

.content-tile {
	background-color: #fff;
	box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.07);
	padding: 24px 24px;
}

</style>

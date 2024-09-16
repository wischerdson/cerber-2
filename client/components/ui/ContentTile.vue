<template>
	<div class="content-tile" :style="{ 'border-radius': `${borderRadius}px` }">
		<slot></slot>
	</div>
</template>

<script setup lang="ts">

withDefaults(defineProps<{ borderRadius?: number }>(), {
	borderRadius: 20
})

</script>

<style scoped lang="scss">

.content-tile {
	position: relative;
	background-color: #fff;
	box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.07);
	padding: 24px 24px;
	z-index: 0;
}

.content-tile:before {
	content: "";
	position: absolute;
	inset: -1px;
	z-index: -1;
	background: linear-gradient(145deg, rgba(#fff, .2), rgba(#fff, .075), rgba(#fff, .05));
	padding: 1px;
	border-radius: inherit;
	mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#862e2e 0 0);
	-webkit-mask-composite: xor;
	mask-composite: exclude;
}

html.dark .content-tile {
	background-color: rgba(#fff, 0);
	background: linear-gradient(145deg, rgba(#fff, .05), rgba(#fff, 0.025), rgba(#fff, 0.04));
	box-shadow: none;
}

</style>

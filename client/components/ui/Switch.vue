<template>
	<div
		class="ui-switch rounded-full w-10 h-6 bg-gray-150 flex items-center p-0.5 cursor-pointer"
		:class="[ isOn ? 'on' : 'off' ]"
		@click="isOn = !isOn"
	>
		<div class="handle w-5 h-5 bg-white rounded-full"></div>
	</div>
</template>

<script setup lang="ts">

const isOn = defineModel<boolean>()

</script>

<style scoped lang="scss">

.ui-switch {
	transition: background-color .2s ease;

	.handle {
		animation: .2s linear forwards;
	}

	&.on {
		background-color: theme('colors.black');

		.handle {
			animation-name: switch-on;
		}
	}

	&.off {
		.handle {
			animation-name: switch-off;
		}
	}
}

@keyframes switch-on {
	0% {
		transform: scaleX(1) translateX(0);
	}
	50% {
		transform: scaleX(1.3) translateX(8px);
	}
	100% {
		transform: scaleX(1) translateX(16px);
	}
}

@keyframes switch-off {
	0% {
		transform: scaleX(1) translateX(16px);
	}
	50% {
		transform: scaleX(1.3) translateX(8px);
	}
	100% {
		transform: scaleX(1) translateX(0);
	}
}

</style>

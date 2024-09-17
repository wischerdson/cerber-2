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
	transition: background-color .25s ease, box-shadow .25s ease;

	.handle {
		animation: .25s cubic-bezier(.4,0,.2,1) forwards;
		transition: margin-left .25s ease, width .25s ease;
	}

	&:active .handle {
		width: 23px;
	}

	&.on {
		background-color: theme('colors.blue.500');
		box-shadow: 0 0 18px -5px theme('colors.blue.500/6');

		.handle {
			animation-name: switch-on;
		}

		&:active .handle {
			margin-left: -3px;
		}
	}

	&.off {
		.handle {
			animation-name: switch-off;
		}
	}
}

@keyframes switch-on {
	from {
		transform: translateX(0);
	}

	to {
		transform: translateX(16px);
	}
}

@keyframes switch-off {
	from {
		transform: translateX(16px);
	}

	to {
		transform: translateX(0);
	}
}

</style>

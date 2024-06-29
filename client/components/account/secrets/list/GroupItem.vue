<template>
	<li class="group-item">
		<TheClickable class="flex items-center space-x-3 rounded-lg py-1.5 px-2 -ml-1.5">
			<FolderIcon class="w-6 h-6" />
			<div>
				<div>
					<span>{{ name }}</span>
				</div>
			</div>
		</TheClickable>
	</li>
</template>

<script setup lang="ts">

import FolderIcon from '~/assets/svg/Colored=folder.fill.svg'
import TheClickable from '~/components/ui/Clickable.vue'

defineProps<{ name: string }>()

</script>

<style scoped lang="scss">

.group-item {
	.clickable {
		width: calc(100% + 10px);
	}

	.clickable:hover {
		background-color: theme('colors.gray.50');
	}
}

</style>

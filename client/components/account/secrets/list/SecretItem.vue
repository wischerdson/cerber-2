<template>
	<li class="secret-item" :class="{ irrelevant: !actual }">
		<TheClickable class="flex items-center space-x-3 rounded-lg py-1.5 px-2 -ml-1.5">
			<div class="relative">
				<div class="secret-icon w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg" v-if="!icon">
					<span class="text-gray-700 font-medium text-lg select-none">{{ firstLetter }}</span>
				</div>
				<div class="secret-icon w-8 h-8 flex items-center justify-center rounded-lg overflow-hidden" v-else-if="icon.type === 'img'">
					<img class="w-full h-full object-contain" :src="icon.value" />
				</div>
				<div class="secret-icon w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg" v-else-if="icon.type === 'icones'">
					<component is="icon" class="text-gray-700" :name="icon.value" size="22px" />
				</div>
				<div class="absolute top-4 bottom-0 -left-2.5 -right-2.5 h-0.5 bg-red-500 z-10 -rotate-45 rounded-full" v-if="!actual"></div>
			</div>
			<div>
				<div>
					<span class="secret-name">{{ name }}</span>
				</div>
			</div>
		</TheClickable>
	</li>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import { computed } from 'vue'

const props = withDefaults(defineProps<{
	name: string
	actual?: boolean
	icon?: {
		type: 'img' | 'icones'
		value: string
	}
}>(), { actual: true })

const firstLetter = computed(() => props.name.slice(0, 1).toUpperCase())

</script>

<style scoped lang="scss">

.secret-item {
	.clickable {
		width: calc(100% + 10px);
	}

	.clickable:hover {
		background-color: theme('colors.gray.50');
	}

	&.irrelevant {
		.secret-icon, .secret-name {
			opacity: .6;
		}
	}
}

</style>

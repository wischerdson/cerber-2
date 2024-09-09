<template>
	<li class="secret-item" :class="{ irrelevant: !secret.isUptodate }">
		<TheClickable class="flex items-center space-x-3 rounded-lg py-1.5 px-2 -ml-1.5" @click="secretsStore.viewSecret(secret)">
			<div class="relative">
				<div class="secret-icon w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg" v-if="!icon || icon.type === 'letter'">
					<span class="text-gray-700 font-medium text-lg select-none">{{ firstLetter }}</span>
				</div>
				<div class="secret-icon w-8 h-8 flex items-center justify-center rounded-lg overflow-hidden" v-else-if="icon.type === 'img'">
					<img class="w-full h-full object-contain" :src="icon.value" />
				</div>
				<div class="secret-icon w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg" v-else-if="icon.type === 'icones'">
					<component is="icon" class="text-gray-700" :name="icon.value" size="22px" />
				</div>
				<div class="absolute top-4 bottom-0 -left-2.5 -right-2.5 h-0.5 bg-red-500 z-10 -rotate-45 rounded-full" v-if="!secret.isUptodate"></div>
			</div>
			<div>
				<div>
					<span class="secret-name">{{ secret.name }}</span>
				</div>
			</div>
		</TheClickable>
	</li>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import { computed } from 'vue'
import { useSecretsStore, type Secret } from '~/store/secrets'

const props = defineProps<{
	secret: Secret
	icon?: {
		type: 'img' | 'icones' | 'letter'
		value?: string
	}
}>()

const firstLetter = computed(() => props.secret.name.slice(0, 1).toUpperCase())
const secretsStore = useSecretsStore()

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

<template>
	<div
		class="secret-icon secret-icon--has-background aspect-square flex items-center justify-center rounded-lg"
		v-if="icon[0] === 'letter'"
	>
		<span class="text-gray-700 dark:text-gray-550 font-medium text-lg select-none">{{ firstLetter }}</span>
		<slot></slot>
	</div>
	<div class="secret-icon aspect-square flex items-center justify-center rounded-lg overflow-hidden" v-else-if="icon[0] === 'img'">
		<img class="w-full h-full object-contain" :src="icon[1]" />
		<slot></slot>
	</div>
	<div class="secret-icon secret-icon--has-background aspect-square flex items-center justify-center rounded-lg" v-else-if="icon[0] === 'icones'">
		<component is="icon" :name="icon[1]" size="22px" />
		<slot></slot>
	</div>
</template>

<script setup lang="ts">

import { computed } from 'vue'

export interface SecretIconProps {
	icon: ['img' | 'icones' | 'letter', string]
}

const props = defineProps<SecretIconProps>()
const firstLetter = computed(() => props.icon[1].slice(0, 1).toUpperCase())

</script>

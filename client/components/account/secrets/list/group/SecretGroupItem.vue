<template>
	<UiClickable
		class="w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg"
		:nuxt-link="{ to: '/' }"
	>
		<icon class="text-gray-800 dark:text-gray-200" name="material-symbols:folder-rounded" size="30px" />
		<span class="font-medium">{{ name }}</span>
	</UiClickable>
</template>

<script setup lang="ts">

import UiClickable from '~/components/ui/Clickable.vue'

export interface SecretGroupProps {
	name: string
}

defineProps<SecretGroupProps>()

</script>

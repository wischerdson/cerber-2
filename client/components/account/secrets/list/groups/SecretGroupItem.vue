<template>
	<UiClickable
		class="w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg"
		:nuxt-link="{ to: { name: 'slug', params: { slug: [group.alias] } } }"
	>
		<icon class="text-gray-800 dark:text-gray-200 shrink-0" name="material-symbols:folder-rounded" size="30px" />
		<span class="font-medium">{{ group.name }}</span>
	</UiClickable>
</template>

<script setup lang="ts">

import UiClickable from '~/components/ui/Clickable.vue'
import type { SecretGroup } from '~/repositories/adapters/secret-group-adapter'

defineProps<{ group: SecretGroup }>()

</script>

<template>
	<UiClickable
		class="secret-item w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg"
		:nuxt-link="{ to: '/' }"
	>
		<SecretIcon class="secret-icon--letter w-7 h-7" :icon="icon" />
		<span class="font-medium">{{ secret.name }}</span>
	</UiClickable>
</template>

<script setup lang="ts">

import type { SecretPreview } from '~/repositories/adapters/secret-adapter'
import UiClickable from '~/components/ui/Clickable.vue'
import SecretIcon, { type SecretIconProps } from '~/components/account/secrets/list/SecretIcon.vue'

defineProps<{ secret: SecretPreview, icon: SecretIconProps['icon'] }>()

</script>

<template>
	<li class="secret-item" :class="{ irrelevant: !secret.isUptodate }">
		<UiClickable class="flex items-center space-x-3 rounded-lg py-1.5 px-2 -ml-1.5" @click="loadSecretDetails(secret.clientCode)">
			<div class="relative">
				<SecretIcon class="secret-icon w-8 h-8" :icon="['letter', secret.name]">
					<div class="absolute inset-0 flex items-center justify-center rounded-lg backdrop-blur-md bg-white/50" v-if="pending">
						<UiSpinner size="18px" />
					</div>
				</SecretIcon>
				<div class="absolute top-4 bottom-0 -left-2.5 -right-2.5 h-0.5 bg-red-500 z-10 -rotate-45 rounded-full" v-if="!secret.isUptodate"></div>
			</div>
			<div>
				<div>
					<span class="secret-name">{{ secret.name }}</span>
				</div>
			</div>
		</UiClickable>
	</li>
</template>

<script setup lang="ts">

import type { SecretPreview } from '~/repositories/adapters/secret-adapter'
import UiClickable from '~/components/ui/Clickable.vue'
import UiSpinner from '~/components/ui/Spinner.vue'
import SecretIcon from '~/components/account/secrets/SecretIcon.vue'
import { ref } from 'vue'
import { useSecretsStore } from '~/store/secrets'

const props = defineProps<{
	secret: SecretPreview
}>()

const secretsStore = useSecretsStore()
const pending = ref(false)

const loadSecretDetails = async (clientCode: string) => {
	if (pending.value) {
		return
	}

	pending.value = true
	await secretsStore.viewSecretDetails(clientCode)
	pending.value = false
}

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

:deep(.secret-icon--has-background) {
	@apply bg-gray-50 dark:bg-gray-900;
}

html.dark {
	.secret-item {
		.clickable:hover {
			background-color: rgba(#fff, .1);
		}
	}
}

</style>

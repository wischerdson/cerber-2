<template>
	<div class="secret-view relative">
		<div>
			<div class="flex space-x-3">
				<div class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg">
					<icon class="text-gray-700" name="material-symbols:database" size="22px" />
				</div>
				<h2 class="flex-1 font-bold text-gray-850 text-1.5xl tracking-wide self-center">{{ secret.name }}</h2>
			</div>

			<p class="mt-4 leading-tight tracking-wide text-gray-700 text-sm">{{ secret.notes }}</p>
		</div>

		<div class="mt-8 space-y-4">
			<CustomFieldView v-for="field in secret.fields" :field="field" :key="field.clientCode" />
		</div>

		<div class="mt-10 flex justify-between items-center">
			<span class="text-xs">
				<span class="text-gray-500">Создано:</span> {{ createdAt }}
			</span>
			<div class="flex space-x-2 ml-auto">
				<TheClickable class="w-8 h-8 flex items-center justify-center" title="Посмотреть историю изменений">
					<icon class="opacity-60" name="material-symbols:history-rounded" size="22px" />
				</TheClickable>
				<TheClickable class="w-8 h-8 bg-red-100 text-[#e30000] rounded-md flex items-center justify-center" title="Удалить">
					<TrashIcon class="w-3.5" />
				</TheClickable>
				<TheClickable class="h-8 bg-blue-100 text-blue-800 px-4 rounded-md text-sm">Изменить</TheClickable>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import type { Secret } from '~/store/secrets'
import TheClickable from '~/components/ui/Clickable.vue'
import TrashIcon from '~/assets/svg/Monochrome=trash.fill.svg'
import CustomFieldView from '~/components/account/secrets/FieldView.vue'
import { computed } from 'vue'
import { formatDate, formatTime } from '~/utils/date'

interface SecretViewProps {
	secret: Secret
}

const props = defineProps<SecretViewProps>()

const createdAt = computed(() => {
	return `${formatDate(props.secret.createdAt)} в ${formatTime(props.secret.createdAt)}`
})

</script>

<style scoped lang="scss">



</style>

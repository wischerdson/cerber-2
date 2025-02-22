<template>
	<div>
		<div class="flex items-center space-x-3">
			<h2 class="font-bold text-gray-850 dark:text-gray-200 text-xl tracking-wide">Новый доступ</h2>
		</div>

		<TheForm class="mt-6" v-model="model" />

		<div class="flex justify-end mt-6">
			<UiButton @click="emit('cancel')">Отменить</UiButton>
			<UiButton color="primary">Создать</UiButton>
		</div>
	</div>
</template>

<script setup lang="ts">

import type { SecretForCreate } from '~/repositories/adapters/secret-adapter'
import UiButton from '~/components/ui/Button.vue'
import TheForm from './Form.vue'
import { uid } from '~/utils/helpers'
import { ref } from 'vue'

const emit = defineEmits<{
	(e: 'cancel'): void
}>()

const model = ref<SecretForCreate>({
	notes: '',
	clientCode: uid(),
	isUptodate: true,
	name: '',
	fields: []
})

</script>

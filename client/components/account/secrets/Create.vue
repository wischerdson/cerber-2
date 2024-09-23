<template>
	<div>
		<div class="flex items-center space-x-3">
			<div class="bg-black/5 dark:bg-white/10 w-9 h-9 flex items-center justify-center rounded-lg">
				<span class="text-2xl font-medium">?</span>
			</div>
			<h2 class="font-bold text-gray-850 dark:text-gray-200 text-1.5xl tracking-wide">Новый доступ</h2>
		</div>
		<TheForm
			class="mt-6"
			v-model="model"
		/>

		<div class="flex justify-end mt-6 space-x-4">
			<UiClickable class="text-gray-700" @click="emit('cancel')">Отменить</UiClickable>
			<UiClickable class="h-10 bg-black text-white px-5 rounded-lg relative" @click="save">
				<div class="absolute inset-0 flex items-center justify-center" v-if="pending">
					<UiSpinner size="24px" />
				</div>
				<span :style="{ opacity: +!pending }">Создать</span>
			</UiClickable>
		</div>
	</div>
</template>

<script setup lang="ts">

import type { SecretForCreate } from '~/repositories/adapters/secret-adapter'
import { ref } from 'vue'
import TheForm from '~/components/account/secrets/Form.vue'
import UiClickable from '~/components/ui/Clickable.vue'
import UiSpinner from '~/components/ui/Spinner.vue'
import { createSecret } from '~/repositories/secrets'
import { uid } from '~/utils/helpers.js'

const emit = defineEmits<{
	(e: 'cancel'): void
}>()

const model = ref<SecretForCreate>({
	notes: 'Работает только из-под корпоративного VPN',
	clientCode: uid(),
	isUptodate: true,
	name: 'FTP',
	fields: [
		{
			label: 'Хост',
			value: 'somesite.ru',
			shortDescription: '',
			secure: false,
			multiline: false,
			clientCode: 'hehe1'
		},
		{
			label: 'Логин',
			value: 'root',
			shortDescription: '',
			secure: false,
			multiline: true,
			clientCode: 'hehe2'
		},
		{
			label: 'Пароль',
			value: '123123',
			shortDescription: 'Какое-то короткое описание для поля',
			secure: true,
			multiline: false,
			clientCode: 'hehe3'
		}
	]
})
const pending = ref(false)

const save = async () => {
	pending.value = true
	await createSecret(model.value)
	pending.value = false
}

</script>

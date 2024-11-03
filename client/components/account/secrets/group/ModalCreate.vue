<template>
	<UiModal class="w-screen max-w-md" v-model="show" @after-leave="reset">
		<h2 class="text-2xl font-medium">Новая группа</h2>
		<form class="mt-6" @submit.prevent="sendForm">
			<div class="space-y-5">
				<UiInput label="Название" :validation-field="useField('name')" />
				<UiTextarea label="Описание" :validation-field="useField('description')" />
			</div>
			<div class="flex justify-end mt-6 space-x-4">
				<UiClickable class="text-gray-700 dark:text-gray-300" @click="show = false" type="button">Отменить</UiClickable>
				<UiClickable class="h-10 bg-black text-white dark:bg-white dark:text-black px-5 rounded-lg relative" type="submit">
					<div class="absolute inset-0 flex items-center justify-center" v-if="pending">
						<UiSpinner size="24px" />
					</div>
					<span :style="{ opacity: +!pending }">Создать</span>
				</UiClickable>
			</div>
		</form>
	</UiModal>
</template>

<script setup lang="ts">

import UiModal from '~/components/ui/Modal.vue'
import UiInput from '~/components/ui/Input.vue'
import UiTextarea from '~/components/ui/Textarea.vue'
import UiClickable from '~/components/ui/Clickable.vue'
import UiSpinner from '~/components/ui/Spinner.vue'
import { ref, watch } from 'vue'
import { useValidation } from '~/composables/use-validation'
import { object, string } from 'yup'

const emit = defineEmits<{
	(e: 'close'): void
	(e: 'create'): void
}>()

const props = defineProps<{ parentId: number }>()

const pending = ref(false)

const show = defineModel<boolean>({ required: true })

const { useField, validate, touchAll, clearAll, resetValues } = useValidation().defineRules(object({
	name: string().required('Введите название группы').max(60, 'Название слишком длинное'),
	description: string().nullable().default(null)
}))

const reset = () => {
	clearAll()
	resetValues()
}

const sendForm = async () => {
	const form = await validate()
	touchAll()

	if (!form) {
		return
	}

	console.log(Object.assign(form, { parentId: props.parentId }))
}

</script>

<template>
	<div
		class="w-full flex items-center gap-3 h-10 px-4 rounded-lg"
		:class="{ 'edit-mode': editMode }"
	>
		<icon class="text-gray-800 dark:text-gray-200 shrink-0" name="material-symbols:folder-rounded" size="30px" />
		<div class="w-full inline-group-input" v-if="editMode">
			<UiInput
				class="new-group-input bg-gray-50 h-8 px-2 rounded-lg -mx-2 font-medium"
				v-click-outside="saveNewName"
				v-model="editedName"
				non-styled
				@blur="onBlur"
				@focus="onFocus"
			/>
		</div>
		<span class="font-medium" v-else>{{ name }}</span>
	</div>
</template>

<script setup lang="ts">

import { onMounted, ref } from 'vue'
import UiInput from '~/components/ui/Input.vue'

export interface EditableSecretGroupProps {
	name: string
	editMode: boolean
}

const emit = defineEmits<{ (e: 'saveName', name: string): void }>()
const props = defineProps<EditableSecretGroupProps>()
const editedName = ref(props.name)

let timeout: NodeJS.Timeout | null = null

const onFocus = () => timeout && clearTimeout(timeout)

const onBlur = () => {
	// Если пользователь переключает язык, то на долю секунды фокус с поля может сняться, что нежелательно
	timeout = setTimeout(() => saveNewName(), 300)
}

const saveNewName = () => {
	timeout && clearTimeout(timeout)
	emit(
		'saveName',
		editedName.value.length ? editedName.value : props.name
	)
}

onMounted(() => {
	const $input = document.querySelector('.new-group-input') as HTMLInputElement

	if ($input) {
		$input.focus()
		$input.select()
	}
})

</script>

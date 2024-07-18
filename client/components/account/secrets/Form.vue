<template>
	<div>
		<!-- <pre>{{ model }}</pre> -->
		<div class="space-y-2 mt-4">
			<div>
				<InputText class="mt-1" v-model="model.name">
					<template #label="{ id }">
						<label class="text-sm text-gray-700" :for="id">Название</label>
					</template>
				</InputText>
			</div>
			<div>
				<HeightAnimation>
					<table class="w-full border-separate border-spacing-y-4">
						<TransitionGroup name="field-list" tag="tbody">
							<FieldEditRow
								v-for="(field, idx) in model.fields"
								:key="`field-${idx}`"
								v-model="model.fields[idx]"
							/>
						</TransitionGroup>
					</table>
				</HeightAnimation>
				<TheClickable class="flex items-center h-9" tabindex="-1" @click="addField">
					<div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mr-2.5" >
						<icon class="text-white" name="material-symbols:add-rounded" size="20px" />
					</div>
					<span class="text-sm text-gray-800">Добавить поле</span>
				</TheClickable>
			</div>
		</div>

		<div class="mt-4">
			<TextArea class="mt-1" v-model="model.notes">
				<template #label="{ id }">
					<label class="text-sm text-gray-700" :for="id">Заметки</label>
				</template>
			</TextArea>
		</div>

		<div class="flex items-center justify-end mt-4 space-x-4">
			<TheClickable class="h-8">
				<span class="text-gray-700 text-sm font-medium">Отменить</span>
			</TheClickable>
			<TheClickable class="h-8 bg-blue-100 text-blue-800 px-4 rounded-md text-sm" @click="emit('save', model)">Сохранить</TheClickable>
		</div>
	</div>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import FieldEditRow from '~/components/account/secrets/FieldEditRow.vue'
import { reactive } from 'vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'

export type Props = {
	name: string
	notes: string
	fields: {
		name: string
		value: string
		secure: boolean
		multiline: boolean
		short_description?: string | null
	}[]
}

const emit = defineEmits<{ (e: 'save', model: Props): void }>()

const props = defineProps<Props>()

const model = reactive<Props>({
	name: props.name,
	notes: props.notes,
	fields: Object.assign(props.fields)
})

const addField = () => {
	model.fields.push({
		name: `Поле #${model.fields.length + 1}`,
		secure: false,
		multiline: false,
		value: '',
		short_description: null
	})
}

</script>

<style scoped lang="scss">

.field-list-move,
.field-list-enter-active,
.field-list-leave-active {
	transition: transform .25s ease, opacity .25s ease;
}

.field-list-enter-from,
.field-list-leave-to {
	opacity: 0;
	transform: translateY(-20px);
}

</style>

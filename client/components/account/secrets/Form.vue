<template>
	<div>
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
					<TransitionGroup class="table w-full relative" tag="div" name="field-list">
						<FieldEditRow
							v-for="(field, idx) in model.fields"
							:key="field.label + field.sort"
							v-model="model.fields[idx]"
							@remove="model.fields.splice(idx, 1)"
						/>
					</TransitionGroup>
				</HeightAnimation>
				<div class="bg-white mt-2 relative z-10">
					<TheClickable class="flex items-center h-9" tabindex="-1" @click="addField">
						<div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mr-2.5" >
							<icon class="text-white" name="material-symbols:add-rounded" size="20px" />
						</div>
						<span class="text-sm text-gray-800">Добавить поле</span>
					</TheClickable>
				</div>
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
			<TheClickable class="h-8 bg-blue-100 text-blue-800 px-4 rounded-md text-sm" @click="emit('save', toRaw(model))">Сохранить</TheClickable>
		</div>
	</div>
</template>

<script setup lang="ts">

import type { FieldModel } from '~/components/account/secrets/FieldEditRow.vue'
import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import FieldEditRow from '~/components/account/secrets/FieldEditRow.vue'
import { reactive, toRaw } from 'vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'

export type Props = {
	name: string
	notes: string
	fields: FieldModel[]
}

const emit = defineEmits<{ (e: 'save', model: Props): void }>()

const props = defineProps<Props>()

const model = reactive<Props>(Object.assign({}, props))

let fieldsCount = model.fields.length

const addField = () => model.fields.push({
	label: `Поле #${++fieldsCount}`,
	secure: false,
	multiline: false,
	value: '',
	shortDescription: null,
	sort: model.fields.length
})

</script>

<style lang="scss">

.field-list-move,
.field-list-enter-active {
	transition: transform .3s ease, opacity .3s ease;
	background-color: #fff;
}

.field-list-leave-active {
	transition: transform .3s ease;
}

.field-list-enter-from {
	opacity: 0;
	transform: translateY(-20px);
}

.field-list-leave-active {
	position: absolute;
	z-index: 0;
	left: 0;
	right: 0;
}

</style>

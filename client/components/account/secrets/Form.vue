<template>
	<div>
		<div class="mt-4">
			<div>
				<UiInput v-model="model.name" label="Название" />
			</div>
			<div>
				<HeightAnimation>
					<TransitionGroup class="w-full relative" tag="div" name="field-list">
						<FieldEditRow
							class="mt-5"
							v-for="(field, idx) in model.fields"
							:key="field.clientCode"
							v-model="model.fields[idx]"
							:first="idx === 0"
							:last="idx === model.fields.length - 1"
							@remove="model.fields.splice(idx, 1)"
							@up="swapFields(idx, -1)"
							@down="swapFields(idx, 1)"
						/>
					</TransitionGroup>
				</HeightAnimation>
				<div class="bg-white mt-4 relative z-10 flex justify-center">
					<TheClickable class="flex items-center h-9" tabindex="-1" @click="addField">
						<div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mr-2.5" >
							<icon class="text-white" name="material-symbols:add-rounded" size="20px" />
						</div>
						<span>Добавить поле</span>
					</TheClickable>
				</div>
			</div>
		</div>

		<div class="mt-4">
			<UiTextarea v-model="model.notes" label="Заметки" />
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

import type { SecretForCreate } from '~/repositories/adapters/secret-adapter'
import UiInput from '~/components/ui/Input.vue'
import UiTextarea from '~/components/ui/Textarea.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import FieldEditRow from '~/components/account/secrets/FieldEditRow.vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { reactive, toRaw } from 'vue'
import { uid } from '~/utils/helpers'

export interface Props extends SecretForCreate {

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
	clientCode: uid()
})

const swapFields = (idx: number, direction: -1 | 1) => {
	const tmp = model.fields[idx]
	model.fields[idx] = model.fields[idx + direction]
	model.fields[idx + direction] = tmp
}

</script>

<style lang="scss">

.field-list-move,
.field-list-enter-active {
	transition: transform .3s ease, opacity .3s ease;
	background-color: #fff;
}

.field-list-leave-active {
	position: absolute;
	width: 100%;
	transition: transform .3s ease, opacity .3s ease;
}

.field-list-enter-from,
.field-list-leave-to {
	opacity: 0;
	transform: scale(.8);
}

</style>

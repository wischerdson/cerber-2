<template>
	<div class="table-row">
		<DraggableCol class="table-cell align-middle" />
		<LabelCol class="table-cell align-middle" v-model="model" @remove="emit('remove')" />
		<ValueCol class="table-cell align-middle" v-model="model" />
		<!-- <div class="table-cell align-middle">
			<div class="h-7 flex items-center justify-end">
				<div class="h-full relative">
					<TheClickable
						class="field-properties-btn h-full rounded-md px-1.5"
						title="Изменить свойства поля"
						tabindex="-1"
						@click="showPopover = true"
					>
						<span class="text-sm font-medium text-gray-700 whitespace-nowrap">{{ model.label }}</span>
					</TheClickable>

					<transition :duration="500" @after-leave="onPopoverClosed">
						<FieldPropertiesPopover
							class="z-10"
							v-if="showPopover"
							v-click-outside="() => showPopover = false"
							:label="model.label"
							:short-description="model.shortDescription"
							:secure="model.secure"
							:multiline="model.multiline"
							@remove="remove"
							@save="save"
						/>
					</transition>
				</div>

				<icon
					class="text-gray-500 -ml-0.5"
					name="material-symbols:chevron-right-rounded"
					size="20px"
				/>
			</div>
		</div>
		<div class="w-full table-cell align-middle">
			<div class="relative w-full">
				<InputText class="!pr-8" v-model="model.value" />
				<div class="absolute top-1 left-1 pointer-events-none" v-if="model.secure">
					<LockIcon class="w-1.5 text-gray-400" />
				</div>
				<div class="absolute inset-y-0 right-0 flex items-center pr-2 z-10">
					<TheClickable class="generate-btn w-6 h-6 flex items-center justify-center rounded-md" title="Сгенерировать" tabindex="-1">
						<icon class="text-gray-500" name="material-symbols:magic-button" />
					</TheClickable>
				</div>
			</div>
		</div> -->
	</div>
</template>

<script setup lang="ts">

import DraggableCol from './field-edit-row/1.Draggable.vue'
import LabelCol from './field-edit-row/2.Label.vue'
import ValueCol from './field-edit-row/3.Value.vue'
import type { FieldProperties } from '~/components/account/secrets/FieldPropertiesPopover.vue'

export type FieldModel = FieldProperties & {
	value: string
	sort: number
}

const emit = defineEmits<{ (e: 'remove'): void }>()

const model = defineModel<FieldModel>({ required: true })

</script>

<style lang="scss" scoped>

.field-properties-btn,
.generate-btn {
	&:hover {
		background-color: #eaeaea;

		span, svg {
			color: theme('colors.black');
		}
	}
}

</style>

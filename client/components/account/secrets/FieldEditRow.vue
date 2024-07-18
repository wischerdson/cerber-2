<template>
	<tr>
		<td class="w-5">
			<div class="-ml-0.5" title="Удалить поле" tabindex="-1">
				<icon class="text-gray-300" name="material-symbols:drag-handle-rounded" size="24px" />
			</div>
		</td>
		<td>
			<div class="h-7 flex items-center justify-end">
				<div class="h-full relative">
					<TheClickable
						class="field-properties-btn h-full rounded-md px-1.5"
						title="Изменить свойства поля"
						tabindex="-1"
						@click="showPopover = true"
					>
						<span class="text-sm font-medium text-gray-700 whitespace-nowrap">{{ model.name }}</span>
					</TheClickable>

					<transition :duration="500">
						<FieldPropertiesPopover class="z-10" v-if="showPopover" v-click-outside="() => showPopover = false" />
					</transition>
				</div>

				<icon
					class="text-gray-500 -ml-0.5"
					name="material-symbols:chevron-right-rounded"
					size="20px"
				/>
			</div>
		</td>
		<td class="w-full">
			<div class="relative w-full flex-1">
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
		</td>
	</tr>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import LockIcon from '~/assets/svg/lock.svg'
import FieldPropertiesPopover from './FieldPropertiesPopover.vue'
import { ref } from 'vue'

export type FieldModel = {
	name: string
	secure: boolean
	multiline: boolean
	value: string
}

const [model] = defineModel<FieldModel>({ required: true })

const showPopover = ref(false)

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

.remove-field-btn {
	&:hover {
		background-color: #eaeaea;
	}
}

</style>

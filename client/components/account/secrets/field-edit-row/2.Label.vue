<template>
	<div>
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
</template>

<script setup lang="ts">

import type { FieldModel } from '~/components/account/secrets/FieldEditRow.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import FieldPropertiesPopover from '~/components/account/secrets/FieldPropertiesPopover.vue'
import type { FieldProperties } from '~/components/account/secrets/FieldPropertiesPopover.vue'
import { ref } from 'vue'

const emit = defineEmits<{ (e: 'remove'): void }>()

const model = defineModel<FieldModel>({ required: true })

const showPopover = ref(false)

let shouldRemove = false

const remove = () => {
	showPopover.value = false
	shouldRemove = true
}

const save = (properties: FieldProperties) => {
	model.value = Object.assign(model.value, properties)
	showPopover.value = false
}

const onPopoverClosed = () => shouldRemove && emit('remove')

</script>

<style lang="scss" scoped>

.field-properties-btn {
	&:hover {
		background-color: #eaeaea;

		span {
			color: theme('colors.black');
		}
	}
}

</style>

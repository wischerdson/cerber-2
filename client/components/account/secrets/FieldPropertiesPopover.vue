<template>
	<div class="popover absolute top-full right-0 rounded-2xl mt-2">
		<div class="popover-inner-content p-5">
			<h4 class="text-lg font-medium">Свойства поля</h4>
			<form class="mt-6" @submit.prevent="emit('save', model)">
				<div>
					<InputText class="mt-1" v-model.lazy="model.label">
						<template #label="{ id }">
							<label class="text-sm text-gray-700" :for="id">Этикетка</label>
						</template>
					</InputText>
				</div>
				<div class="mt-4">
					<TextArea class="mt-1" :rows="1" allow-shrink v-model.lazy="shortDescription">
						<template #label="{ id }">
							<div class="flex items-center justify-between">
								<label class="text-sm text-gray-700" :for="id">Короткое описание</label>
								<div class="text-gray-400 text-xs">
									{{ shortDescription.length }}/140
								</div>
							</div>
						</template>
					</TextArea>
				</div>

				<div class="mt-6 space-y-4">
					<div class="flex items-center justify-between">
						<div class="flex items-center space-x-2">
							<LockIcon class="w-3 ml-1 mr-1.5 text-gray-500" />
							<div class="tracking-wide">Защищенное</div>
						</div>
						<TheSwitch v-model="model.secure" />
					</div>
					<div class="flex items-center justify-between">
						<div class="flex items-center space-x-2">
							<icon class="text-gray-500" name="material-symbols:wrap-text-rounded" size="22px" />
							<div class="tracking-wide">Многострочное</div>
						</div>
						<TheSwitch v-model="model.multiline" />
					</div>
				</div>
			</form>
			<div class="mt-6 flex items-center justify-between">
				<TheClickable class="text-primary-red" title="Удалить поле" @click="emit('remove')">
					<TrashIcon class="w-5 h-5" />
				</TheClickable>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import TheSwitch from '~/components/ui/Switch.vue'
import TrashIcon from '~/assets/svg/Monochrome=trash.fill.svg'
import LockIcon from '~/assets/svg/lock.svg'
import { computed } from 'vue'

export type FieldProperties = {
	label: string
	shortDescription: string | null
	secure: boolean
	multiline: boolean
}

const emit = defineEmits<{
	(e: 'remove'): void
	(e: 'save', properties: FieldProperties): void
}>()

const model = defineModel<FieldProperties>({ required: true })

const shortDescription = computed({
	get: () => model.value.shortDescription || '',
	set: v => model.value.shortDescription = v || null
})

</script>

<style scoped lang="scss">

.popover {
	width: 320px;
	box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.1);
	background-color: #fff;

	@supports (backdrop-filter: blur(18px)) {
		background-color: rgba(#fff, 0.25);
		backdrop-filter: blur(18px) saturate(1.5);
	}

	&.v-enter-active {
		transform-origin: top right;
		transition: opacity .3s ease, transform .35s cubic-bezier(.25,.1,.25,1.45);

		.popover-inner-content {
			transition: opacity .2s ease .2s;
		}
	}

	&.v-leave-active {
		transition: opacity .2s ease;
		pointer-events: none;
	}

	&.v-enter-from {
		opacity: 0;
		transform: scale(.7);

		.popover-inner-content {
			opacity: 0;
		}
	}

	&.v-leave-to {
		opacity: 0;
	}
}

</style>

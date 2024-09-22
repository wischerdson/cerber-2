<template>
	<div class="popover absolute top-5 left-0 rounded-2xl mt-2">
		<div class="popover-inner-content p-5">
			<h4 class="text-lg font-medium">Свойства поля</h4>
			<form class="mt-6" @submit.prevent="emit('save', model)">
				<div>
					<UiInput v-model.lazy="model.label" label="Этикетка" />
				</div>
				<div class="mt-4">
					<UiTextarea class="mt-1" :rows="1" allow-shrink v-model="shortDescription" label="Короткое описание" />
				</div>

				<div class="mt-6 space-y-4">
					<div class="flex items-center justify-between">
						<div class="flex items-center space-x-2">
							<div class="flex items-center justify-center w-6 h-6 rounded-[5px] bg-green-600/10">
								<LockIcon class="w-2.5 text-green-600" />
							</div>
							<div class="tracking-wide">Защищенное</div>
						</div>
						<UiSwitch v-model="model.secure" />
					</div>
					<div class="flex items-center justify-between">
						<div class="flex items-center space-x-2">
							<div class="flex items-center justify-center w-6 h-6 rounded-[5px] bg-gray-600/10">
								<icon class="text-black" name="material-symbols:wrap-text-rounded" size="20px" />
							</div>
							<div class="tracking-wide">Многострочное</div>
						</div>
						<UiSwitch v-model="model.multiline" />
					</div>
				</div>
			</form>
		</div>
	</div>
</template>

<script setup lang="ts">

import UiInput from '~/components/ui/Input.vue'
import UiTextarea from '~/components/ui/Textarea.vue'
import UiSwitch from '~/components/ui/Switch.vue'
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
		background-color: rgba(#fff, 0.75);
		backdrop-filter: blur(18px) saturate(1.5);
	}

	&.v-enter-active {
		transform-origin: top left;
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

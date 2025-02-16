<template>
	<div>
		<div class="relative">
			<transition :duration="500" @after-leave="onPopoverClosed">
				<FieldPropertiesPopover
					class="z-20"
					v-if="showPopover"
					v-click-outside="() => showPopover = false"
					v-model="model"
					@remove="remove"
				/>
			</transition>
			<component :is="model.multiline ? UiTextarea : UiInput" v-model="model.value">
				<template #before="{ id }">
					<div class="flex items-end mb-1.5">
						<div class="flex items-center gap-1.5">
							<UiClickable
								class="button-next-to-label flex items-center justify-center w-[22px] h-[22px] rounded-[5px] bg-black/5 dark:bg-white/10 text-red-500"
								title="Удалить поле"
								@click="emit('remove')"
							>
								<icon name="material-symbols:close-rounded" size="18px" />
							</UiClickable>
							<UiClickable
								class="button-next-to-label flex items-center justify-center w-[22px] h-[22px] rounded-[5px] bg-black/5 dark:bg-white/10"
								title="Изменить свойства поля"
								@click="showPopover = true"
							>
								<PencilIcon class="w-3" />
							</UiClickable>
							<UiLabel :for="id">{{ model.label }}</UiLabel>
							<LockIcon class="w-2 pt-px text-green-700" v-if="model.secure" />
						</div>
						<div class="flex ml-auto">
							<UiClickable
								class="change-position-button flex items-center justify-center w-5 h-5 rounded-[5px] text-black/50 dark:text-white/50"
								:class="{ disabled: first }"
								title="Передвинуть наверх"
								@click="emit('up')"
							>
								<icon name="material-symbols:keyboard-arrow-up-rounded" size="20px" />
							</UiClickable>
							<UiClickable
								class="change-position-button flex items-center justify-center w-5 h-5 rounded-[5px] text-black/50 dark:text-white/50"
								:class="{ disabled: last }"
								title="Передвинуть вниз"
								@click="emit('down')"
							>
								<icon name="material-symbols:keyboard-arrow-down-rounded" size="20px" />
							</UiClickable>
						</div>
					</div>
				</template>
			</component>

			<div class="absolute top-0 right-0 flex items-center mt-7 pr-1.5 pt-1.5 z-10">
				<UiClickable class="generate-btn w-6 h-6 flex items-center justify-center rounded-md" title="Сгенерировать" tabindex="-1">
					<icon class="text-gray-500" name="material-symbols:magic-button" />
				</UiClickable>
			</div>
		</div>
		<p class="text-xs mt-1 tracking-wide text-gray-500 leading-snug" v-if="model.shortDescription">
			{{ model.shortDescription }}
		</p>
	</div>
</template>

<script setup lang="ts">

import type { FieldProperties } from '~/components/account/secrets/FieldPropertiesPopover.vue'
import UiInput from '~/components/ui/Input.vue'
import UiTextarea from '~/components/ui/Textarea.vue'
import UiLabel from '~/components/ui/Label.vue'
import UiClickable from '~/components/ui/Clickable.vue'
import LockIcon from '~/assets/svg/lock.svg'
import PencilIcon from '~/assets/svg/Monochrome=applepencil.gen1.svg'
import FieldPropertiesPopover from '~/components/account/secrets/FieldPropertiesPopover.vue'
import { ref } from 'vue'

export type FieldModel = FieldProperties & {
	value: string
}

const emit = defineEmits<{
	(e: 'remove'): void
	(e: 'up'): void
	(e: 'down'): void
}>()

defineProps<{ first: boolean, last: boolean }>()

const model = defineModel<FieldModel>({ required: true })

const showPopover = ref(false)

let shouldRemove = false

const remove = () => {
	showPopover.value = false
	shouldRemove = true
}

const onPopoverClosed = () => shouldRemove && emit('remove')

</script>

<style lang="scss" scoped>

.generate-btn {
	backdrop-filter: blur(4px);
	background-color: rgba(#fff, .7);

	&:hover {
		background-color: #eaeaea;

		span, svg {
			color: theme('colors.black');
		}
	}
}

.button-next-to-label {
	&:hover {
		background-color: rgba(#000, .1);
	}
}

html.dark {
	.generate-btn {
		backdrop-filter: blur(4px);
		background-color: rgba(#000, .5);

		&:hover {
			background-color: rgba(#fff, .15);

			span, svg {
				color: theme('colors.white');
			}
		}
	}

	.button-next-to-label {
		&:hover {
			background-color: rgba(#fff, .2);
		}
	}
}

.change-position-button {
	&:not(.disabled):hover {
		background-color: rgba(#000, .05);
		color: #000;
	}

	&.disabled {
		pointer-events: none;
		color: rgba(#000, .15);
	}
}

html.dark {
	.change-position-button {
		&:not(.disabled):hover {
			background-color: rgba(#fff, .1);
			color: #fff;
		}

		&.disabled {
			color: rgba(#fff, .15);
		}
	}
}

</style>

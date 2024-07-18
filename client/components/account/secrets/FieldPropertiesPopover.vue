<template>
	<div class="popover absolute top-full right-0 rounded-2xl mt-2">
		<div class="popover-inner-content p-5">
			<h4 class="text-lg font-medium">Свойства поля</h4>
			<div class="mt-6">
				<div>
					<InputText class="mt-1">
						<template #label="{ id }">
							<label class="text-sm text-gray-700" :for="id">Этикетка</label>
						</template>
					</InputText>
				</div>
				<div class="mt-4">
					<TextArea class="mt-1" :rows="1" allow-shrink v-model="model.shortDescription">
						<template #label="{ id }">
							<div class="flex items-center justify-between">
								<label class="text-sm text-gray-700" :for="id">Короткое описание</label>
								<div class="text-gray-400 text-xs">{{ model.shortDescription.length }}/140</div>
							</div>
						</template>
					</TextArea>
				</div>

				<div class="mt-6 space-y-4">
					<!-- <hr class="border-gray-150 mb-2.5"> -->
					<div class="flex items-center justify-between">
						<!-- <div class="w-[18px] h-[18px] flex items-center justify-center rounded-[5px] bg-slate-900 text-white">
							<icon name="material-symbols:check-rounded" size="22px" />
						</div> -->
						<div class="flex items-center space-x-2">
							<div class="w-5 text-center ml-px">
								<LockIcon class="w-3 ml-1" />
							</div>
							<div class="text-gray-800 tracking-wide">Защищенное</div>
						</div>
						<TheSwitch />
					</div>
					<!-- <hr class="border-gray-150 my-2.5"> -->
					<div class="flex items-center justify-between">
						<!-- <div class="w-[18px] h-[18px] flex items-center justify-center rounded-[5px] bg-slate-900 text-white">
							<icon name="material-symbols:check-rounded" size="22px" />
						</div> -->
						<div class="flex items-center space-x-2">
							<icon class="" name="material-symbols:wrap-text-rounded" size="22px" />
							<div class="text-gray-800 tracking-wide">Многострочное</div>
						</div>
						<TheSwitch />
					</div>
					<!-- <hr class="border-gray-150 mt-2.5"> -->
					<!-- <div class="flex items-center space-x-2">
						<div class="w-[18px] h-[18px] flex items-center justify-center rounded-[5px] bg-slate-900 text-white">
							<icon name="material-symbols:check-rounded" size="22px" />
						</div>
						<div class="text-gray-800 tracking-wide text-sm">Многострочное</div>
					</div> -->
				</div>
			</div>
			<div class="mt-6 flex items-center justify-between">
				<TheClickable class="text-primary-red" title="Удалить поле">
					<TrashIcon class="w-5 h-5" />
				</TheClickable>
				<TheClickable class="h-8 bg-gray-50 border border-gray-150 text-slate-800 px-4 rounded-md text-sm">Сохранить</TheClickable>
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
import { reactive } from 'vue'

const model = reactive({
	shortDescription: ''
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

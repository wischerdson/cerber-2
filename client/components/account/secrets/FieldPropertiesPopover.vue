<template>
	<div class="popover absolute right-0 rounded-2xl p-5">
		<h3 class="text-lg font-medium">Свойства поля</h3>
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

			<div class="mt-4 space-y-4">
				<div class="flex items-center space-x-2">
					<div class="w-[18px] h-[18px] flex items-center justify-center rounded-[5px] bg-slate-900 text-white">
						<icon name="material-symbols:check-rounded" size="22px" />
					</div>
					<div class="text-gray-800 tracking-wide text-sm">Защищенное</div>
				</div>
				<div class="flex items-center space-x-2">
					<div class="w-[18px] h-[18px] flex items-center justify-center rounded-[5px] bg-slate-900 text-white">
						<icon name="material-symbols:check-rounded" size="22px" />
					</div>
					<div class="text-gray-800 tracking-wide text-sm">Многострочное</div>
				</div>
			</div>
		</div>
		<div class="mt-8 flex items-center justify-between">
			<TheClickable class="text-primary-red" title="Удалить поле">
				<TrashIcon class="w-5 h-5" />
			</TheClickable>
			<!-- <TheClickable class="bg-black text-white px-3 h-8 rounded-lg">
				<span class="text-sm">Сохранить</span>
			</TheClickable> -->
			<TheClickable class="h-8 bg-gray-50 border border-gray-150 text-slate-800 px-4 rounded-md text-sm">Сохранить</TheClickable>
		</div>
	</div>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import TrashIcon from '~/assets/svg/Monochrome=trash.fill.svg'
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
}

</style>

<template>
	<div class="spaces-manager">
		<SpacesSwitch class="pointer-events-auto" @manage-spaces="showManagerModal = true" />

		<UiModal class="w-screen max-w-3xl" v-model="showManagerModal">
			<h2 class="text-2xl font-medium">Управление пространствами</h2>
			<div class="flex mt-6">
				<div class="w-full max-w-80">
					<div class="spaces-list bg-gray-50 rounded-lg p-2 font-medium">
						<UiClickable class="spaces-item flex w-full h-9 rounded-md items-center px-3">
							<span>
								Личное
							</span>
						</UiClickable>
						<UiClickable class="spaces-item flex w-full h-9 rounded-md items-center px-3" title="Очень длинное название, которое не вмещается">
							<span class="max-w-full overflow-hidden text-ellipsis whitespace-nowrap">
								Очень длинное название, которое не вмещается
							</span>
						</UiClickable>
						<UiClickable class="spaces-item flex w-full h-9 rounded-md items-center px-3">
							Ipsum
						</UiClickable>
						<UiClickable class="spaces-item flex w-full h-9 rounded-md items-center px-3">
							Коммерческие проекты
						</UiClickable>
						<UiClickable class="spaces-item flex w-full h-9 rounded-md items-center px-3">
							Компания
						</UiClickable>
						<UiClickable class="spaces-item flex w-full h-9 rounded-md items-center px-3">
							Стажировка
						</UiClickable>
					</div>
				</div>
				<div class="w-full">

				</div>
			</div>
		</UiModal>
	</div>
</template>

<script setup lang="ts">

import { ref } from 'vue'
import SpacesSwitch from '~/components/account/TopBar/SpacesSwitch.vue'
import UiModal from '~/components/ui/Modal.vue'
import UiClickable from '~/components/ui/Clickable.vue'

const showManagerModal = ref(false)

</script>

<style lang="scss" scoped>

.spaces-manager {
	border-radius: 9999px;
	background-color: theme('colors.gray.50');
	position: relative;
}

html.dark {
	.spaces-manager {
		background: linear-gradient(145deg, rgba(#fff, .0875), rgba(#fff, 0.05), rgba(#fff, 0.05));
	}
}

.spaces-item {
	&:hover {
		background-color: theme('colors.gray.150');
	}
}

</style>

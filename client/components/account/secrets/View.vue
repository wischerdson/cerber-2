<template>
	<div class="secret-view relative">
		<div>
			<div class="flex space-x-3">
				<div class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg">
					<icon class="text-gray-700" name="material-symbols:database" size="22px" />
				</div>
				<h2 class="flex-1 font-bold text-gray-850 text-1.5xl tracking-wide self-center">База данных</h2>
			</div>

			<p class="mt-4 leading-tight tracking-wide text-gray-700 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
		</div>

		<div class="mt-8 space-y-4">
			<CustomFieldView label="URL" content="https://sk-dev.ru/phpmyadmin" :secure="false" />
			<CustomFieldView label="Логин" content="panteric" :secure="false" />
			<CustomFieldView label="Секретный ключ" content="panteric" :secure="true" />
		</div>

		<div class="mt-10 flex justify-between items-center">
			<span class="text-xs">
				<span class="text-gray-500">Создано:</span> 24 декабря в 12:45
			</span>
			<div class="flex space-x-2 ml-auto">
				<TheClickable class="w-8 h-8 flex items-center justify-center" title="Посмотреть историю изменений">
					<icon class="opacity-60" name="material-symbols:history-rounded" size="22px" />
				</TheClickable>
				<TheClickable class="w-8 h-8 bg-red-100 text-[#e30000] rounded-md flex items-center justify-center" title="Удалить">
					<TrashIcon class="w-3.5" />
				</TheClickable>
				<TheClickable class="h-8 bg-blue-100 text-blue-800 px-4 rounded-md text-sm">Изменить</TheClickable>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import TrashIcon from '~/assets/svg/Monochrome=trash.fill.svg'
import CustomFieldView from '~/components/account/secrets/FieldView.vue'

</script>

<style scoped lang="scss">



</style>

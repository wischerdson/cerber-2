<template>
	<div>
		<div class="space-y-2">
			<div>
				<InputText class="mt-1">
					<template #label="{ id }">
						<label class="text-sm text-gray-700" :for="id">Название</label>
					</template>
				</InputText>
			</div>
			<div>
				<table class="w-full border-separate border-spacing-y-4">
					<tbody>
						<CustomFieldEdit name="Веб-сайт" />
						<CustomFieldEdit name="Логин" />
						<CustomFieldEdit name="Пароль" />
					</tbody>
				</table>
				<TheClickable class="flex items-center h-9" tabindex="-1">
					<div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mr-2.5" >
						<icon class="text-white" name="material-symbols:add-rounded" size="20px" />
					</div>
					<span class="text-sm text-blue-500">Добавить поле</span>
				</TheClickable>
			</div>
		</div>

		<div class="mt-4">
			<TextArea class="mt-1">
				<template #label="{ id }">
					<label class="text-sm text-gray-700" :for="id">Заметки</label>
				</template>
			</TextArea>
		</div>

		<div class="flex items-center justify-end mt-4 space-x-4">
			<TheClickable class="h-8">
				<span class="text-gray-700 text-sm font-medium">Отменить</span>
			</TheClickable>
			<TheClickable class="h-8 bg-blue-100 text-blue-800 px-4 rounded-md text-sm">Сохранить</TheClickable>
		</div>
	</div>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import CustomFieldEdit from '~/components/account/secrets/CustomFieldEdit.vue'

</script>

<style scoped lang="scss">


</style>

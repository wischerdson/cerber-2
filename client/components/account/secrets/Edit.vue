<template>
	<form>
		<div class="space-y-4">
			<TextField>
				<template #label>Название</template>
			</TextField>
			<div class="bg-gray-50 rounded-xl">

			</div>
			<div class="flex items-center pt-5">
				<div class="cursor-grab mr-2">
					<icon class="text-gray-600" name="system-uicons:drag" size="30px" />
				</div>

				<div class="-mt-5 flex-1">
					<TextField>
						<template #label>Логин</template>
					</TextField>
				</div>
				<TheClickable class="w-9 self-stretch flex flex-0 items-center justify-center text-gray-400 hover:text-gray-700" title="Настроить поле" type="button">
					<!-- <GearIcon class="gear-icon h-4 w-4" /> -->
					<icon name="mingcute:more-3-fill" size="20px" />
				</TheClickable>
				<!-- <TheClickable class="w-4 h-4 rounded-full bg-[#e30000] flex items-center justify-center">
					<icon class="text-white" name="material-symbols:add-rounded" size="20px" />
					<icon class="text-white" name="material-symbols:remove-rounded" size="18px" />
				</TheClickable> -->
			</div>

			<TextArea>
				<template #label>Заметки</template>
			</TextArea>
		</div>
	</form>
</template>

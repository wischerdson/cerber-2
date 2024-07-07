<template>
	<tr>
		<td class="w-5">
			<TheClickable class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center mr-2.5" title="Удалить поле" tabindex="-1">
				<icon class="text-white" name="material-symbols:remove-rounded" size="18px" />
			</TheClickable>
		</td>
		<td>
			<div class="h-9 flex items-center justify-end">
				<TheClickable class="h-full -mt-0.5" tabindex="-1">
					<span class="text-sm text-blue-500 whitespace-nowrap">{{ name }}</span>
				</TheClickable>
				<icon
					class="text-gray-500"
					name="material-symbols:chevron-right-rounded"
					size="20px"
				/>
			</div>
		</td>
		<td class="w-full">
			<div class="relative w-full flex-1">
				<InputText />
			</div>
		</td>
	</tr>
</template>

<script setup lang="ts">

import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import TheClickable from '~/components/ui/Clickable.vue'

defineProps<{
	name: string
}>()

</script>

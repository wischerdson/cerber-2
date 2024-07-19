<template>
	<div class="w-full">
		<div class="relative w-full">
			<InputText class="!pr-8" v-model="model.value" />
			<div class="absolute top-1 left-1 pointer-events-none" v-if="model.secure">
				<LockIcon class="w-1.5 text-gray-400" />
			</div>
			<div class="absolute inset-y-0 right-0 flex items-center pr-2 z-10">
				<TheClickable class="generate-btn w-6 h-6 flex items-center justify-center rounded-md" title="Сгенерировать" tabindex="-1">
					<icon class="text-gray-500" name="material-symbols:magic-button" />
				</TheClickable>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import type { FieldModel } from '~/components/account/secrets/FieldEditRow.vue'
import InputText from '~/components/ui/input/Text.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import LockIcon from '~/assets/svg/lock.svg'

const model = defineModel<FieldModel>({ required: true })

</script>

<style lang="scss" scoped>

.generate-btn {
	&:hover {
		background-color: #eaeaea;

		svg {
			color: theme('colors.black');
		}
	}
}

</style>

<template>
	<div>
		<div>
			<div>
				<UiInput v-model="model.name" label="Название" />
			</div>
			<div class="mt-2">
				<HeightAnimation>
					<TransitionGroup
						class="w-full relative"
						tag="div"
						name="field-list"
					>
						<FieldEditRow
							class="mt-5"
							v-for="(field, idx) in model.fields"
							:key="field.clientCode"
							v-model="model.fields[idx]"
							:first="idx === 0"
							:last="idx === model.fields.length - 1"
							@remove="model.fields.splice(idx, 1)"
							@up="swapFields(idx, -1)"
							@down="swapFields(idx, 1)"
						/>
					</TransitionGroup>
				</HeightAnimation>

				<div class="mt-4">
					<UiButton class="gap-1" color="secondary" size="sm" tabindex="-1" @click="addField">
						<icon class="-ml-1" name="material-symbols:add-rounded" size="18px" />
						<span>Добавить поле</span>
					</UiButton>
				</div>
			</div>
		</div>

		<div class="mt-7 relative z-10">
			<UiTextarea v-model="model.notes" label="Заметки" />
		</div>
	</div>
</template>

<script setup lang="ts">

import type { SecretForCreate } from '~/repositories/adapters/secret-adapter'
import UiInput from '~/components/ui/Input.vue'
import UiTextarea from '~/components/ui/Textarea.vue'
import UiButton from '~/components/ui/Button.vue'
import FieldEditRow from '~/components/account/secrets/FieldEditRow.vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { uid } from '~/utils/helpers'

const model = defineModel<SecretForCreate>({ required: true })

let fieldsCount = model.value.fields.length

const addField = () => model.value.fields.push({
	label: `Поле #${++fieldsCount}`,
	secure: false,
	multiline: false,
	value: '',
	shortDescription: null,
	clientCode: uid()
})

const swapFields = (idx: number, direction: -1 | 1) => {
	const tmp = model.value.fields[idx]
	model.value.fields[idx] = model.value.fields[idx + direction]
	model.value.fields[idx + direction] = tmp
}

</script>

<style lang="scss">

.field-list-move,
.field-list-enter-active {
	transition: transform .3s ease, opacity .3s ease;
	background-color: #fff;
}

html.dark {
	.field-list-move,
	.field-list-enter-active {
		background-color: transparent;
		backdrop-filter: blur(20px);
	}
}

.field-list-leave-active {
	position: absolute;
	width: 100%;
	transition: transform .3s ease, opacity .3s ease;
}

.field-list-enter-from,
.field-list-leave-to {
	opacity: 0;
	transform: scale(.8);
}

</style>

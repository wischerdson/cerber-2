<template>
	<div class="form-group">
		<label class="form-label" :for="input?.id">
			<slot name="label"></slot>
		</label>
		<div class="relative">
			<input
				class="form-control"
				type="text"
				v-uid
				autocomplete="off"
				v-bind="useAttrs()"
				v-model="model"
				v-on:[validationTouchEvent]="validationField && validationField.touch()"
				ref="input"
			>
			<div class="absolute inset-0 pointer-events-none">
				<slot name="layer-above-field"></slot>
				<div class="absolute right-0 inset-y-0 flex items-center px-2" v-if="props.validationField && props.validationField.hasErrors()">
					<ExclamationMark class="text-red-1 w-5 h-5" />
				</div>
			</div>
		</div>
		<div class="form-text">
			<slot name="post-scriptum"></slot>
		</div>
		<div class="validation-error text-sm text-red-1 mt-1" v-if="validationField && validationField.hasErrors()">
			<span>{{ validationField.getError() }}</span>
		</div>
	</div>
</template>

<script setup lang="ts">

import type { InputHTMLAttributes } from 'vue'
import type { FieldContext } from '~/composables/use-validation'
import { useAttrs, ref } from '#imports'
import ExclamationMark from '~/assets/svg/Monochrome=exclamationmark.circle.fill.svg'

interface Props extends /* @vue-ignore */ InputHTMLAttributes {
	label?: string | null
	validationField?: FieldContext<any>
	validationTouchEvent?: string
}

const input = ref<null | HTMLElement>(null)

defineOptions({ inheritAttrs: false })

const props = withDefaults(defineProps<Props>(), {
	validationTouchEvent: 'change'
})

const [model] = defineModel({
	set(value) {
		if (props.validationField) {
			props.validationField.setValue(value)
		}

		return value
	}
})

</script>

<style lang="scss">

.form-label {
	font-size: .875rem;
	margin-bottom: 6px;
	display: block;
	color: #818181;
}

.form-control {
	transition: border-color .15s ease;
	background-color: theme('colors.dark.gray-3');
	height: 46px;
	padding: 0 16px;
	border: 1px solid rgba(#000, 0);
	border-radius: 8px;
	display: block;
	width: 100%;
	font-size: 1rem;

	&:focus {
		border: 1px solid lighten(#1d1d1d, 20%);
	}
}

</style>

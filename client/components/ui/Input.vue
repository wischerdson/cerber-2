<template>
	<div>
		<slot name="before" :id="id"></slot>
		<UiLabel v-if="label" class="mb-1.5" :for="id">{{ label }}</UiLabel>
		<div class="relative" v-if="validationField">
			<input
				:class="classes"
				:id="id"
				type="text"
				autocomplete="off"
				v-model="model"
				v-on:[validationTouchEvent]="validationField && validationField.touch()"
				v-bind="useAttrs()"
			/>
			<div class="absolute inset-0 pointer-events-none">
				<transition>
					<div class="ui-input__exclamation-mark-icon absolute right-0 inset-y-0 flex items-center px-2" v-if="validationField.hasErrors()">
						<IconExclamationMark class="text-red-500 w-5 h-5" />
					</div>
				</transition>
			</div>
		</div>
		<input
			v-else
			:class="classes"
			:id="id"
			type="text"
			autocomplete="off"
			v-model="model"
			v-bind="useAttrs()"
		/>
		<UiValidationError class="mt-1" v-if="validationField" :show="validationField.hasErrors()">
			{{ validationField.getError() }}
		</UiValidationError>
		<slot name="after" :id="id"></slot>
	</div>
</template>

<script setup lang="ts">

import type { FieldContext } from '~/composables/use-validation'
import { computed, useAttrs, useId, withDefaults, defineProps } from 'vue'
import IconExclamationMark from '~/assets/svg/Monochrome=exclamationmark.circle.fill.svg'
import UiLabel from './Label.vue'
import UiValidationError from './ValidationError.vue'

export interface InputProps {
	invalid?: boolean
	label?: string
	nonStyled?: boolean
	size?: 'base' | null
	validationField?: FieldContext<any>
	validationTouchEvent?: string
}

defineOptions({ inheritAttrs: false })

const id = useId()

const props = withDefaults(defineProps<InputProps>(), {
	invalid: false,
	nonStyled: false,
	size: 'base',
	validationTouchEvent: 'change'
})

const model = defineModel({
	set(value) {
		if (props.validationField) {
			props.validationField.setValue(value)
		}

		return value
	},
	get(value) {
		return props.validationField ? props.validationField.getValue() : value
	}
})

const classes = computed(() => {
	if (props.nonStyled) {
		return 'ui-input--non-styled'
	}

	const list = ['ui-input']
	props.size && list.push(`ui-input--${props.size}`)

	if (props.invalid || props.validationField?.hasErrors()) {
		list.push(`ui-input--invalid`)
	}

	return list
})

</script>

<style lang="scss">

.ui-input--non-styled {
	appearance: none;
	background-color: rgba(#000, 0);
	background-image: none;
	border-radius: 0;
	border-width: 0;
	color: inherit;
	font-family: inherit;
	font-size: 1rem;
	font-weight: inherit;
	letter-spacing: inherit;
	line-height: inherit;
	margin: 0;
	padding: 0;

	&:focus {
		outline: none;
	}
}

.ui-input {
	@extend .ui-input--non-styled;

	border-radius: 8px;
	border: 1px solid rgba(#000, .16);
	display: block;
	transition: .15s ease;
	transition-property: border-color, background-color;
	width: 100%;

	&:focus {
		border-color: rgba(#000, 1);
	}
}

.ui-input--base {
	height: 36px;
	padding: 0 12px;
}

.ui-input--invalid {
	background-color: rgba(#ef4444, .1);
	border-color: rgba(#ef4444, .5);

	&:focus {
		border-color: rgba(#ef4444, 1);
	}
}

.ui-input__exclamation-mark-icon {
	&.v-enter-active, &.v-leave-active {
		transition: opacity .25s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
	}
}

</style>

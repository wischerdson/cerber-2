<template>
	<div>
		<slot name="before" :id="id"></slot>
		<UiLabel v-if="label" class="mb-1.5" :for="id">{{ label }}</UiLabel>
		<div class="relative" v-if="validationField">
			<textarea
				:class="classes"
				:id="id"
				autocomplete="off"
				:rows="rows"
				v-model="model"
				ref="$textarea"
				v-on:[validationTouchEvent]="validationField && validationField.touch()"
				v-bind="useAttrs()"
			></textarea>
			<div class="absolute inset-0 pointer-events-none">
				<transition>
					<div class="ui-textarea__exclamation-mark-icon absolute right-0 inset-y-0 flex items-center px-2" v-if="validationField.hasErrors()">
						<IconExclamationMark class="text-red-500 w-5 h-5" />
					</div>
				</transition>
			</div>
		</div>
		<textarea
			v-else
			:class="classes"
			:id="id"
			autocomplete="off"
			:rows="rows"
			v-model="model"
			ref="$textarea"
			v-bind="useAttrs()"
		></textarea>
		<UiValidationError class="mt-1" v-if="validationField" :show="validationField.hasErrors()">
			{{ validationField.getError() }}
		</UiValidationError>
		<slot name="after" :id="id"></slot>
	</div>
</template>

<script setup lang="ts">

import type { FieldContext } from '~/composables/use-validation'
import { computed, useAttrs, useId, ref, onMounted } from 'vue'
import IconExclamationMark from '~/assets/svg/Monochrome=exclamationmark.circle.fill.svg'
import UiLabel from './Label.vue'
import UiValidationError from './ValidationError.vue'

export interface InputProps {
	allowShrink?: boolean
	autoHeight?: boolean
	invalid?: boolean
	label?: string
	nonStyled?: boolean
	rows?: number
	size?: 'base' | null
	validationField?: FieldContext<any>
	validationTouchEvent?: string
}

defineOptions({ inheritAttrs: false })

const id = useId()

const props = withDefaults(defineProps<InputProps>(), {
	allowShrink: false,
	autoHeight: true,
	invalid: false,
	nonStyled: false,
	rows: 3,
	size: 'base',
	validationTouchEvent: 'change'
})

const $textarea = ref<HTMLElement>()

const setHeight = () => {
	if (!$textarea.value) {
		return
	}

	const tx = ($textarea.value as HTMLElement)

	if (tx.scrollHeight > tx.offsetHeight || props.allowShrink) {
		tx.style.height = '0'
		tx.style.height = `${tx.scrollHeight + 2}px`
	}
}

const model = defineModel({
	set(value) {
		setHeight()

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
		return 'ui-textarea--non-styled'
	}

	const list = ['ui-textarea']
	props.size && list.push(`ui-textarea--${props.size}`)

	if (props.invalid || props.validationField?.hasErrors()) {
		list.push(`ui-textarea--invalid`)
	}

	if (props.allowShrink) {
		list.push(`ui-textarea--non-resizable`)
	}

	return list
})

onMounted(() => setHeight())

</script>

<style lang="scss">

.ui-textarea--non-styled {
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
	resize: vertical;

	&:focus {
		outline: none;
	}
}

.ui-textarea {
	@extend .ui-textarea--non-styled;

	border-radius: 8px;
	border: 1px solid rgba(#000, .16);
	display: block;
	line-height: 1.25;
	transition-duration: .15s;
	transition-timing-function: ease;
	transition-property: border-color, background-color;
	width: 100%;

	&:focus {
		border-color: #000;
	}
}

.ui-textarea--non-resizable {
	resize: none;
}

.ui-textarea--base {
	min-height: 36px;
	padding: 7px 12px;
}

.ui-textarea--invalid {
	background-color: rgba(#ef4444, .1);
	border-color: rgba(#ef4444, .5);

	&:focus {
		border-color: #ef4444;
	}
}

.ui-textarea__exclamation-mark-icon {
	&.v-enter-active, &.v-leave-active {
		transition: opacity .25s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
	}
}

html.dark .ui-textarea {
	border: 1px solid rgba(#fff, .2);

	&:focus {
		border-color: rgba(#fff, .5);
	}
}

</style>

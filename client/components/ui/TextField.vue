<template>
	<div class="form-group">
		<label class="form-label" :for="input?.id" v-if="!disableLabel">
			<slot name="label"></slot>
		</label>
		<div class="relative">
			<input
				class="form-control"
				:class="{ invalid: validationField?.hasErrors() }"
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
				<transition>
					<div class="absolute right-0 inset-y-0 flex items-center px-2" v-if="validationField && validationField.hasErrors()">
						<ExclamationMark class="text-[#bf4c44] w-5 h-5" />
					</div>
				</transition>
			</div>
		</div>
		<div class="form-text">
			<slot name="post-scriptum"></slot>
		</div>
		<HeightAnimation>
			<transition>
				<div class="validation-error text-sm text-[#bf4c44] mt-1" v-if="validationField && validationField.hasErrors()">
					<span class="tracking-wide font-light">{{ validationField.getError() }}</span>
				</div>
			</transition>
		</HeightAnimation>
	</div>
</template>

<script setup lang="ts">

import type { InputHTMLAttributes } from 'vue'
import type { FieldContext } from '~/composables/use-validation'
import { useAttrs, ref } from '#imports'
import ExclamationMark from '~/assets/svg/Monochrome=exclamationmark.circle.fill.svg'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'

interface Props extends /* @vue-ignore */ InputHTMLAttributes {
	disableLabel?: boolean
	validationField?: FieldContext<any>
	validationTouchEvent?: string
}

const input = ref<null | HTMLElement>(null)

defineOptions({ inheritAttrs: false })

const props = withDefaults(defineProps<Props>(), {
	validationTouchEvent: 'change',
	disableLabel: false
})

const [model] = defineModel({
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

</script>

<style lang="scss">

.form-label {
	font-size: .875rem;
	margin-bottom: 6px;
	display: block;
	color: #818181;
}

.form-control {
	background-color: #eaeaea;
	transition: border-color .15s ease;
	height: 46px;
	padding: 0 16px;
	border: 1px solid rgba(#000, 0);
	border-radius: 8px;
	display: block;
	width: 100%;
	font-size: 1rem;

	&:where(.dark *) {
		background-color: #1d1d1d;
	}

	&:focus {
		border: 1px solid darken(#1d1d1d, 20%);

		&:where(.dark *) {
			border: 1px solid lighten(#1d1d1d, 20%);
		}

		&.invalid {
			border-color: #9d2a23;
		}
	}

	&.invalid {
		background-color: rgba(#9d2a23, .1);
	}
}

.form-group {
	.v-enter-active, .v-leave-active {
		transition: opacity .25s ease;
	}

	.v-enter-from, .v-leave-to {
		opacity: 0;
	}
}

</style>

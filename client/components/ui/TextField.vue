<template>
	<div class="form-group">
		<label class="form-label" :for="$input?.id" v-if="!disableLabel">
			<slot name="label"></slot>
		</label>
		<div class="relative">
			<input
				class="form-control"
				:class="[
					{ invalid: validationField?.hasErrors() },
					`size-${size}`
				]"
				type="text"
				v-uid
				autocomplete="off"
				v-bind="useAttrs()"
				v-model="model"
				v-on:[validationTouchEvent]="validationField && validationField.touch()"
				ref="$input"
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
	size?: 'base' | 'lg'
}

const $input = ref<null | HTMLElement>(null)

defineOptions({ inheritAttrs: false })

const props = withDefaults(defineProps<Props>(), {
	validationTouchEvent: 'change',
	disableLabel: false,
	size: 'base'
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

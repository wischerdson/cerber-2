<template>
	<div class="form-group">
		<label class="form-label" :for="$textarea?.id" v-if="!disableLabel">
			<slot name="label"></slot>
		</label>
		<div class="relative">
			<textarea
				class="form-control"
				:class="[
					{ invalid: validationField?.hasErrors() },
					`size-${size}`
				]"
				type="text"
				v-uid
				autocomplete="off"
				v-bind="useAttrs()"
				:rows="rows"
				v-model="model"
				v-on:[validationTouchEvent]="validationField && validationField.touch()"
				ref="$textarea"
			></textarea>
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

import type { TextareaHTMLAttributes } from 'vue'
import type { FieldContext } from '~/composables/use-validation'
import { useAttrs, ref, onMounted, onUnmounted } from '#imports'
import ExclamationMark from '~/assets/svg/Monochrome=exclamationmark.circle.fill.svg'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'

interface Props extends /* @vue-ignore */ TextareaHTMLAttributes {
	disableLabel?: boolean
	validationField?: FieldContext<any>
	validationTouchEvent?: string
	size?: 'base' | 'lg'
	autoHeight?: boolean
	allowShrink?: boolean
	rows?: number
}

const $textarea = ref<HTMLElement>()

defineOptions({ inheritAttrs: false })

const props = withDefaults(defineProps<Props>(), {
	validationTouchEvent: 'change',
	disableLabel: false,
	size: 'base',
	autoHeight: true,
	allowShrink: false,
	rows: 3
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

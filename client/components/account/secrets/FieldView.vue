<template>
	<div class="pt-5 secret-field" :class="{ secure: field.secure, visible }">
		<div class="-mt-5">
			<div class="flex">
				<span class="text-sm text-gray-700 mb-1">{{ field.label }}</span>
			</div>
		</div>
		<div class="flex space-x-2">
			<div class="w-full flex-1 relative rounded-lg py-3 px-3 bg-gray-50">
				<span class="secret-field-content">{{ field.value }}</span>

				<div class="absolute inset-0 pr-12 p-2 pointer-events-none">
					<SpoilerContent class="spoiler w-full h-full" v-if="field.secure" />
				</div>

				<div class="absolute right-0 inset-y-0 z-10 flex">
					<TheClickable
						class="copy-content-btn w-10 h-10 flex items-center justify-center rounded-lg"
						@click="copy"
						title="Скопировать"
					>
						<icon class="text-gray-500" name="material-symbols:content-copy-rounded" size="20px" />
					</TheClickable>
				</div>

				<transition :duration="150">
					<div class="copied-badge absolute right-8 z-10 inset-y-0 flex items-center pr-3 select-none" v-if="showCopiedBadge">
						<div class="text-xs tracking-wide bg-green-200 text-green-700 px-2 py-1 rounded-full">Скопировано</div>
					</div>
				</transition>
			</div>

			<TheClickable class="show-spoiler-content-btn w-7 self-stretch flex items-center justify-center" v-if="field.secure" @click="forceVisible = !forceVisible">
				<EyeSlashIcon class="w-5 text-gray-500 -mt-px" v-if="forceVisible" />
				<EyeIcon class="w-5 text-gray-500" v-else />
			</TheClickable>
			<TheClickable
				class="w-7 self-stretch flex items-center justify-center"
				title="Перейти по ссылке"
				tag="a"
				target="_blank"
				:href="url"
				v-if="isUrl"
			>
				<icon class="text-blue-500" name="tabler:external-link" size="20px" />
			</TheClickable>
		</div>
		<p class="text-xs mt-1 pb-2 tracking-wide text-gray-500 leading-snug" v-if="field.shortDescription">{{ field.shortDescription }}</p>
	</div>
</template>

<script setup lang="ts">

import type { SecretField } from '~/repositories/adapters/secret-adapter'
import { computed, ref } from 'vue'
import { isUrl as _isUrl, hasHttpProtocol } from '~/utils/helpers'
import EyeIcon from '~/assets/svg/Monochrome=eye.fill.svg'
import EyeSlashIcon from '~/assets/svg/Monochrome=eye.slash.fill.svg'
import TheClickable from '~/components/ui/Clickable.vue'
import SpoilerContent from '~/components/ui/SpoilerContent.vue'

interface FieldViewProps {
	field: SecretField
}

const props = defineProps<FieldViewProps>()

const forceVisible = ref(false)
const showCopiedBadge = ref(false)

const isUrl = computed(() => _isUrl(props.field.value))
const url = computed(() => {
	return hasHttpProtocol(props.field.value) ? props.field.value : `https://${props.field.value}`
})
const visible = computed(() => !props.field.secure || forceVisible.value)

const copy = () => {
	showCopiedBadge.value = true

	setTimeout(() => showCopiedBadge.value = false, 1000)
}

</script>

<style scoped lang="scss">

.secret-field {
	&.secure {
		.secret-field-content {
			transition: opacity .3s ease;
			opacity: 0;
			user-select: none;
		}

		.spoiler {
			transition: opacity .3s ease;
			opacity: 1;
		}

		&.visible {
			.secret-field-content {
				opacity: 1;
				user-select: auto;
			}

			.spoiler {
				opacity: 0;
			}
		}
	}
}

.copy-content-btn {
	transition: background-color .15s ease;

	svg {
		transition: color .15s ease;
	}

	&:hover {
		background-color: theme('colors.gray.100');

		svg {
			color: theme('colors.gray.700');
		}
	}
}

.copied-badge {
	&.v-enter-active, &.v-leave-active {
		transition: opacity .15s ease, transform .15s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
		transform: scale(.9) translateX(10px);
	}
}

.show-spoiler-content-btn {
	transition: background-color .15s ease;

	svg {
		transition: color .15s ease;
	}

	&:hover {


		svg {
			color: theme('colors.gray.700');
		}
	}
}

</style>

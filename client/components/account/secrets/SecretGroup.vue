<template>
	<UiClickable class="secret-group block w-full">
		<div class="group-icon aspect-square bg-black/5 dark:bg-white/10 rounded-2xl grid grid-cols-2 grid-rows-2 items-start gap-1.5 p-1.5">
			<SecretIcon
				v-for="icon in preview.slice(0, 3)"
				:icon="icon"
				:key="icon[1]"
			/>
			<div class="aspect-square grid grid-cols-2 gap-0.5" v-if="preview.length > 3">
				<SecretIcon
					class="secret-icon--mini"
					v-for="icon in preview.slice(3, 7)"
					:icon="icon"
					:key="icon[1]"
				/>
			</div>
		</div>
		<div class="group-name text-xs leading-none mt-1">{{ name }}</div>
	</UiClickable>
</template>

<script setup lang="ts">

import type { SecretIconProps } from '~/components/account/secrets/SecretIcon.vue'
import UiClickable from '~/components/ui/Clickable.vue'
import SecretIcon from '~/components/account/secrets/SecretIcon.vue'

export interface SecretGroupProps {
	name: string
	preview: SecretIconProps['icon'][]
}

const props = defineProps<SecretGroupProps>()

</script>

<style lang="scss" scoped>

:deep(.secret-icon--has-background) {
	@apply bg-gray-150 dark:bg-white/10;
}

:deep(.secret-icon) {
	span {
		color: rgba(#000, .5);
	}
}

html.dark {
	.secret-icon {
		:deep(span), :deep(svg) {
			color: rgba(#fff, .5);
		}
	}
}

:deep(.secret-icon--mini) {
	border-radius: 4px;

	span {
		font-size: .6rem;
		color: rgba(#000, .5);
	}
}

html.dark {
	.secret-icon--mini {
		:deep(span), :deep(svg) {
			color: rgba(#fff, .5);
		}
	}
}

.secret-group {
	.group-icon {
		transition: transform .2s ease;
	}

	&:hover {
		.group-icon {
			transform: scale(1.05);
		}
	}
}

.group-name {
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2;
	line-clamp: 2;
	overflow: hidden;
	text-overflow: ellipsis;
}

</style>

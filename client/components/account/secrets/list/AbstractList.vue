<template>
	<div class="list" :class="{ shown }">
		<div class="flex justify-between">
			<UiClickable class="show-hide-btn flex items-center justify-center text-gray-700 dark:text-gray-550" @click="shown = !shown">
				<span class="text-sm">{{ name }}</span>
				<icon
					class="chevron-right -mb-0.5 ml-1 -mr-2"
					name="material-symbols:chevron-right-rounded"
					size="22px"
				/>
			</UiClickable>
			<UiClickable class="add-btn rounded-md text-gray-700" :title="addItemTitle" @click="emits('add')">
				<icon size="20px" name="material-symbols:add-rounded" />
			</UiClickable>
		</div>

		<HeightAnimation>
			<transition name="list">
				<div class="mt-2.5" v-if="shown">
					<slot></slot>
				</div>
			</transition>
		</HeightAnimation>
	</div>
</template>

<script setup lang="ts">

import UiClickable from '~/components/ui/Clickable.vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { ref } from 'vue'

const props = withDefaults(defineProps<{
	name: string
	addItemTitle: string
	showOnInit?: boolean
}>(), { showOnInit: void 0 })

const emits = defineEmits<{ (e: 'add'): void }>()

const shown = ref(props.showOnInit !== false)

</script>

<style scoped lang="scss">

.list {
	.show-hide-btn {
		svg {
			transition: transform .15s ease;
		}
	}

	&.shown {
		.show-hide-btn {
			svg {
				transform: rotate(90deg);
			}
		}
	}
}

.list-leave-active {
	transition: opacity .15s ease;
}

.list-enter-active {
	transition: opacity .15s ease .15s;
}

.list-enter-from, .list-leave-to {
	opacity: 0;
}

.add-btn {
	width: 22px;
	height: 22px;

	&:hover {
		color: theme('colors.gray.900');
		background-color: #eaeaea;
	}
}

html.dark {
	.add-btn {
		&:hover {
			color: theme('colors.gray.100');
			background-color: rgba(#fff, .1);
		}
	}
}

</style>

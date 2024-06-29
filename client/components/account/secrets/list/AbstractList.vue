<template>
	<div class="list" :class="{ shown }">
		<TheClickable class="show-hide-btn flex items-center justify-center text-gray-700 ml-1" @click="shown = !shown">
			<span class="text-sm">{{ name }}</span>
			<icon
				class="chevron-right -mb-0.5 ml-1 -mr-2"
				name="material-symbols:chevron-right-rounded"
				size="22px"
			/>
		</TheClickable>
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

import TheClickable from '~/components/ui/Clickable.vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { ref } from 'vue'

defineProps<{ name: string }>()

const shown = ref(true)

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
	transition: opacity .25s ease .15s;
}

.list-enter-from, .list-leave-to {
	opacity: 0;
}

</style>

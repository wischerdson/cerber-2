<template>
	<div class="search relative" :class="{ focused }">
		<input
			class="search-field pl-9 h-10"
			type="text"
			v-uid
			autocomplete="off"
			placeholder="Поиск"
			v-model="searchQuery"
			ref="$input"
			@focus="focused = true"
			@blur="focused = false"
		>
		<div class="absolute inset-0 pointer-events-none">
			<div class="h-full flex justify-between items-center pl-2 pr-4">
				<icon class="text-black/50 dark:text-white/50" size="22px" name="ph:magnifying-glass" />

				<transition>
					<Clickable
						class="clear-search-btn w-7 h-7 -mr-2 rounded-md flex items-center justify-center pointer-events-auto"
						v-if="showClearBtn"
						@click="searchQuery = ''"
					>
						<icon size="20px" name="material-symbols:close-rounded" />
					</Clickable>
				</transition>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import Clickable from '~/components/ui/Clickable.vue'
import { ref, computed, onMounted, onUnmounted } from '#imports'
import { useTypingDetector } from '~/composables/use-typing-detector';

const searchQuery = ref('')
const showClearBtn = computed(() => !!searchQuery.value.length)
const focused = ref(false)
const $input = ref<HTMLInputElement>()
let stopTypingDetection: () => void

onMounted(() => {
	stopTypingDetection = useTypingDetector(() => $input.value?.focus())
})

onUnmounted(() => stopTypingDetection())

</script>

<style scoped lang="scss">

.search {
	transition: transform .3s ease;

	&:hover, &.focused {
		transform: scale(1.028);

		.search-field {
			box-shadow: 0 5px 16px 0 rgba(0, 0, 0, 0.1);
		}
	}
}

.search-field {
	width: 100%;
	background-color: #fff;
	border-radius: 10px;
	box-shadow: 0 5px 16px 0 rgba(0, 0, 0, 0.05);
	transition: box-shadow .3s ease;
}

.clear-search-btn {
	opacity: .5;
	transition: opacity .15s ease;

	&.v-enter-active, &.v-leave-active {
		transition: opacity .15s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
	}

	&:hover {
		opacity: 1;
	}
}

html.dark {
	.clear-search-btn {
		&:hover {
			background-color: darken(#fff, 91%);
		}
	}
}

</style>

<template>
	<div class="search relative" :class="{ focused }">
		<input
			class="search-field pl-8 pr-7 h-10"
			type="text"
			autocomplete="off"
			placeholder="Поиск"
			v-model="searchQuery"
			ref="$input"
			@focus="focused = true"
			@blur="focused = false"
		>
		<div class="absolute inset-0 pointer-events-none">
			<div class="h-full flex justify-between items-center pl-2 pr-4">
				<icon class="text-black/60 dark:text-white/50" size="18px" name="ph:magnifying-glass" />

				<Clickable
					class="clear-search-btn w-4 h-4 text-black/60 dark:text-white/50 -mr-2 rounded-full flex items-center justify-center pointer-events-auto"
					v-if="showClearBtn"
					@click="searchQuery = ''"
					title="Очистить поле"
				>
					<icon size="16px" name="material-symbols:close-rounded" />
				</Clickable>
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
	border-radius: 10px;
	transition: .3s ease;
	transition-property: transform, box-shadow;
	box-shadow: 0 5px 16px 0 rgba(0, 0, 0, 0.05);
	background-color: #fff;

	&:before {
		content: "";
		position: absolute;
		inset: -1px;
		z-index: -1;
		background: linear-gradient(145deg, rgba(#fff, .2), rgba(#fff, .075), rgba(#fff, .075));
		padding: 1px;
		border-radius: inherit;
		mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#862e2e 0 0);
		-webkit-mask-composite: xor;
		mask-composite: exclude;
		transition: inherit;
		transition-property: background-color;
	}

	&.focused {
		transform: scale(1.028);
		box-shadow: 0 5px 16px 0 rgba(0, 0, 0, 0.1);
	}
}

.search-field {
	width: 100%;
	border-radius: inherit;
}

html.dark {
	.search {
		background-color: rgba(#fff, 0);
		background: linear-gradient(145deg, rgba(#fff, .075), rgba(#fff, 0.025), rgba(#fff, 0.04));

		&.focused {
			box-shadow: 0 0 30px 0 rgba(#fff, .2);
		}
	}
}

</style>

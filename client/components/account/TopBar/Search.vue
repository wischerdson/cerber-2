<template>
	<TextField class="!pl-12" style="width: 420px;" placeholder="Поиск" v-model="searchQuery">
		<template #layer-above-field>
			<div class="h-full flex justify-between items-center px-4">
				<icon class="text-black/50 dark:text-white/50" size="24px" name="ph:magnifying-glass" />

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
		</template>
	</TextField>
</template>

<script setup lang="ts">

import TextField from '~/components/ui/TextField.vue'
import Clickable from '~/components/ui/Clickable.vue'
import { ref, computed } from '#imports'

const searchQuery = ref('')
const showClearBtn = computed(() => !!searchQuery.value.length)

</script>

<style lang="scss" scoped>

.clear-search-btn {
	opacity: .75;
	transition: background-color .15s ease, opacity .15s ease;

	&.v-enter-active, &.v-leave-active {
		transition: opacity .15s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
	}

	&:hover {
		background-color: darken(#fff, 14%);
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

<template>
	<TextField class="!pl-9 !h-10" placeholder="Поиск" v-model="searchQuery" disable-label>
		<template #layer-above-field>
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
	opacity: .5;
	transition: opacity .15s ease;

	&.v-enter-active, &.v-leave-active {
		transition: opacity .15s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
	}

	&:hover {
		// background-color: darken(#fff, 14%);
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

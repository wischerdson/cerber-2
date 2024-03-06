<template>
	<div class="w-80 dark:bg-gray-900/50 bg-gray-50/50 backdrop-blur-xl rounded-2xl">
		<div class="mt-4 px-4">
			<Search />
		</div>
		<div class="mt-4 px-4">
			<TheClickable class="menu-item">
				<ClockIcon class="mr-2 h-4 w-4" />
				<span>Недавние</span>
			</TheClickable>
			<TheClickable class="menu-item">
				<icon class="mr-1 -ml-0.5 -mt-0.5" size="22px" name="material-symbols:star-rounded" />
				<span>Избранные</span>
			</TheClickable>
		</div>
		<hr class="mx-4 mt-4 border-gray-200 dark:border-gray-850">
		<div class="mt-4 px-4 pb-4">
			<div class="flex items-center justify-between mb-2">
				<h2 class="text-sm text-gray-400 dark:text-gray-750">Группы</h2>
				<TheClickable class="add-group-btn rounded-md text-gray-500" title="Добавить новую группу">
					<icon size="24px" name="material-symbols:add-rounded" />
				</TheClickable>
			</div>
			<TheClickable class="menu-item">
				<icon
					class="chevron-right text-gray-500 -ml-2"
					name="material-symbols:chevron-right-rounded"
					size="22px"
				/>
				<span>Какая-то группа</span>
				<!-- <icon class="ml-auto" size="18px" name="svg-spinners:90-ring-with-bg" /> -->
			</TheClickable>
			<TheClickable class="menu-item">
				<icon
					class="chevron-right text-gray-500 -ml-2"
					name="material-symbols:chevron-right-rounded"
					size="22px"
				/>
				<span>Lorem ipsum dolor sit</span>
			</TheClickable>
		</div>
	</div>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import Search from '~/components/account/Search.vue'
import ClockIcon from '~/assets/svg/Monochrome=clock.fill.svg'

</script>

<style lang="scss" scoped>

.add-group-btn {
	width: 28px;
	height: 28px;

	&:hover {
		color: theme('colors.gray.600');
		background-color: #eaeaea;
	}
}

html.dark {
	.add-group-btn {
		&:hover {
			color: theme('colors.gray.200');
			background-color: #1d1d1d;
		}
	}
}

.menu-item {
	@apply text-gray-600 flex w-full h-9 rounded-md items-center px-3;

	.chevron-right {
		transition: transform .15s ease;
	}

	&:hover {
		background-color: #eaeaea;
	}
}

html.dark {
	.menu-item {
		color: theme('colors.gray.200');

		&:hover {
			background-color: #1d1d1d;
		}
	}
}

</style>

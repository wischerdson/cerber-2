<template>
	<div class="w-full max-w-72 backdrop-blur-xl rounded-2xl">
		<div>
			<Search />
		</div>
		<div class="mt-4">
			<UiClickable class="menu-item">
				<ClockIcon class="mr-2 h-4 w-4" />
				<span>Недавние</span>
			</UiClickable>
			<UiClickable class="menu-item">
				<icon class="mr-1 -ml-0.5 -mt-0.5" size="22px" name="material-symbols:star-rounded" />
				<span>Избранные</span>
			</UiClickable>
		</div>
		<hr class="mt-4 border-gray-150 dark:border-gray-850">
		<div class="mt-4 pb-4">
			<div class="flex items-center justify-between mb-2">
				<h2 class="text-sm text-gray-400 dark:text-gray-750">Группы</h2>
				<UiClickable class="add-group-btn rounded-md text-gray-500" title="Добавить новую группу">
					<icon size="24px" name="material-symbols:add-rounded" />
				</UiClickable>
			</div>

			<ul class="grid grid-cols-3 gap-2.5 gap-y-5">
				<li>
					<SecretGroup name="Название группы" :preview="[
						['letter', 'ftp'],
						['img', 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png'],
						['img', 'https://www.apple.com/favicon.ico'],
						['letter', 'ssh'],
						['letter', 'ftp'],
						['letter', 'ftp'],
						['img', 'https://odekolon-shop.ru/image/catalog/4-100x100.png'],
					]"/>
				</li>
				<li>
					<SecretGroup name="Lorem ipsum dolor sit" :preview="[
						['letter', 'А'],
						['img', 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png'],
					]"/>
				</li>
				<li>
					<SecretGroup name="Lorem ipsum, dolor sit amet consectetur" :preview="[
						['img', 'https://odekolon-shop.ru/image/catalog/4-100x100.png'],
						['img', 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png'],
						['img', 'https://www.apple.com/favicon.ico'],
					]"/>
				</li>
				<li>
					<SecretGroup name="Deserve" :preview="[
						['letter', 'ftp'],
						['letter', 'ssh'],
						['letter', 'ftp'],
						['letter', 'ftp'],
					]"/>
				</li>
			</ul>
		</div>
	</div>
</template>

<script setup lang="ts">

import UiClickable from '~/components/ui/Clickable.vue'
import Search from '~/components/account/Search.vue'
import ClockIcon from '~/assets/svg/Monochrome=clock.fill.svg'
import SecretGroup from './secrets/SecretGroup.vue'

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

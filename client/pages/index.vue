<template>
	<div class="flex justify-center gap-6">
		<div class="grow max-w-2xl">
			<TheSearch />

			<UiContentTile class="mt-6 pb-6">
				<div class="px-2 pt-4">
					<div class="w-full flex items-center rounded-full text-gray-600 dark:text-gray-500 text-sm px-1.5">
						<NuxtLink to="/">
							<UiClickable class="px-2.5 h-[26px] flex items-center hover:bg-gray-50 dark:hover:bg-gray-850 hover:text-black dark:hover:text-gray-200 rounded-md" tag="div">
								<HouseIcon class="w-4.5" />
							</UiClickable>
						</NuxtLink>
						<icon name="material-symbols:chevron-right-rounded" size="18px" />
						<NuxtLink to="/">
							<UiClickable class="px-2 h-[26px] flex items-center hover:bg-gray-50 dark:hover:bg-gray-850 hover:text-black dark:hover:text-gray-200 rounded-md" tag="div">
								<span class="whitespace-nowrap truncate">Panteric</span>
							</UiClickable>
						</NuxtLink>
						<icon name="material-symbols:chevron-right-rounded" size="18px" />
						<NuxtLink to="/">
							<UiClickable class="px-2 h-[26px] flex items-center hover:bg-gray-50 dark:hover:bg-gray-850 hover:text-black dark:hover:text-gray-200 rounded-md" tag="div">
								<span class="whitespace-nowrap truncate">Маркетплейсы</span>
							</UiClickable>
						</NuxtLink>
					</div>
				</div>
				<div class="px-6 mt-4">
					<h1 class="font-medium text-xl">Реклама и маркетинг</h1>

					<hr class="w-full border-white dark:border-gray-850 mt-6 mb-6">

					<AbstractList name="Группы" :showOnInit="true" add-item-title="Добавить группу">
						<ul class="-mx-4">
							<li>
								<NuxtLink to="/">
									<UiClickable class="w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg">
										<icon class="text-gray-800 dark:text-gray-200" name="material-symbols:folder-rounded" size="30px" />
										<span class="font-medium">Ozon</span>
									</UiClickable>
								</NuxtLink>
							</li>
							<li>
								<NuxtLink to="/">
									<UiClickable class="w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg">
										<icon class="text-gray-800 dark:text-gray-200" name="material-symbols:folder-rounded" size="30px" />
										<span class="font-medium">Wildberries</span>
									</UiClickable>
								</NuxtLink>
							</li>
						</ul>
					</AbstractList>

					<AbstractList class="mt-4" name="Доступы" :showOnInit="true" add-item-title="Создать новый доступ">
						<ul class="-mx-4">
							<li>
								<UiClickable class="secret-item w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg" :nuxt-link="{ to: '/' }">
									<SecretIcon class="secret-icon--letter w-7 h-7" :icon="['letter', 'SSH']" />
									<span class="font-medium">FTP</span>
								</UiClickable>
							</li>
							<li>
								<UiClickable class="secret-item w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg" :nuxt-link="{ to: '/' }">
									<SecretIcon class="secret-icon w-7 h-7" :icon="['img', 'https://dzen.ru/favicon.ico']" />
									<span class="font-medium">Дзен</span>
								</UiClickable>
							</li>
							<li>
								<UiClickable class="secret-item w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg" :nuxt-link="{ to: '/' }">
									<SecretIcon class="secret-icon w-7 h-7" :icon="['img', 'https://www.wildberries.ru/favicon.ico']" />
									<span class="font-medium">Wildberries</span>
								</UiClickable>
							</li>
							<li>
								<UiClickable class="secret-item w-full flex items-center gap-3 h-10 hover:bg-gray-50 dark:hover:bg-gray-850 px-4 rounded-lg" :nuxt-link="{ to: '/' }">
									<div class="aspect-square flex items-center justify-center">
										<svg class="w-7 h-7 text-gray-800 dark:text-gray-450" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.389 17.979" fill="currentColor"><path d="M23.027 3.027v11.934c0 2.012-1.015 3.018-3.066 3.018H3.066C1.026 17.979 0 16.973 0 14.96V3.027C0 1.016 1.025 0 3.066 0h16.895c2.05 0 3.066 1.016 3.066 3.027ZM8.955 8.047a.646.646 0 0 0-.644.654c0 .361.283.654.644.654h3.838a.648.648 0 0 0 .654-.654.648.648 0 0 0-.654-.654ZM4.062 4.658l2.422 1.455-2.421 1.475c-.792.469-.127 1.68.751 1.133l2.891-1.817c.576-.361.547-1.22 0-1.572l-2.89-1.807c-.88-.546-1.553.645-.753 1.133Z"/></svg>
									</div>
									<span class="font-medium">SSH</span>
								</UiClickable>
							</li>
						</ul>
					</AbstractList>
				</div>
			</UiContentTile>
		</div>
		<UiContentTile class="py-6 h-[400px] grow max-w-md px-6">
			<h2 class="font-semibold text-xl">Какой-то доступ</h2>
		</UiContentTile>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import UiContentTile from '~/components/ui/ContentTile.vue'
import HouseIcon from '~/assets/svg/house.fill.svg'
import UiClickable from '~/components/ui/Clickable.vue'
import TheSearch from '~/components/account/Search.vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretIcon from '~/components/account/secrets/SecretIcon.vue'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

<style>

.secret-item {
	&:hover {
		.secret-icon--letter {
			background-color: var(--color-gray-100);
		}
	}
}

html.dark {
	.secret-item {
		&:hover {
			.secret-icon--letter {
				background-color: var(--color-gray-800);
			}
		}
	}
}

</style>

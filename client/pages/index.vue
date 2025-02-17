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
				<!-- <hr class="w-full border-white dark:border-gray-850 my-5"> -->
				<div class="px-6 mt-4">
					<h1 class="font-medium text-xl">Реклама и маркетинг</h1>

					<AbstractList class="mt-8" name="Группы" :showOnInit="true" add-item-title="Создать группу">
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

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

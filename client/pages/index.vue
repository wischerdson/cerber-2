<template>
	<div class="flex justify-center">
		<UiContentTile class="grow max-w-2xl h-[500px]">
			<div>
				<div class="-mx-4 -mt-4">
					<div class="w-full h-8 flex items-center rounded-full bg-gray-100/80 dark:bg-gray-950 text-gray-600 dark:text-gray-500 text-sm px-1.5">
						<a class="px-2.5 py-1 hover:bg-white dark:hover:bg-gray-850 hover:text-black dark:hover:text-gray-200 rounded-full">
							<HouseIcon class="w-4.5" />
						</a>
						<icon name="material-symbols:chevron-right-rounded" size="18px" />
						<a class="px-2 py-1 hover:bg-white dark:hover:bg-gray-850 hover:text-black dark:hover:text-gray-200 rounded-full">
							<span class="whitespace-nowrap truncate">Panteric</span>
						</a>
						<icon name="material-symbols:chevron-right-rounded" size="18px" />
						<a class="px-2 py-1 hover:bg-white dark:hover:bg-gray-850 hover:text-black dark:hover:text-gray-200 rounded-full">
							<span class="whitespace-nowrap truncate">Маркетплейсы</span>
						</a>
					</div>
				</div>

				<div class="mt-5">
					<h1 class="font-medium text-xl">Реклама и маркетинг</h1>
				</div>
			</div>
		</UiContentTile>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import UiContentTile from '~/components/ui/ContentTile.vue'
import HouseIcon from '~/assets/svg/house.fill.svg'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

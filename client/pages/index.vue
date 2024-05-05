<template>
	<div class="container py-7">
		<div>
			<h1 class="font-bold text-gray-900 dark:text-gray-100 text-2.5xl">Доступы</h1>
		</div>
		<div class="flex items-start gap-6 mt-5">
			<Sidebar />

			<div class="flex-1 dark:bg-gray-900/50 bg-white/50 page-tile backdrop-blur-xl rounded-2xl">
				<div class="p-6">
					<div class="flex items-center justify-between">
						<div class="flex">
							<h2 class="font-medium text-gray-700 dark:text-gray-150 text-xl">Какая-то группа</h2>
							<TheClickable class="ml-4 text-gray-300" title="Изменить название">
								<EditIcon class="h-4 w-4" />
							</TheClickable>
						</div>
						<div class="flex space-x-2">
							<TheClickable
								class="flex items-center justify-center w-10 h-10 rounded-full dark:text-gray-250 text-gray-700"
								title="Настройки группы"
							>
								<GearIcon class="h-4 w-4" />
							</TheClickable>
							<TheClickable
								class="add-password-btn bg-gray-100 text-black flex items-center justify-center h-10 px-4 rounded-full"
							>
								<icon class="mr-2" size="24px" name="material-symbols:add-rounded" />
								<span>Добавить</span>
							</TheClickable>
						</div>
					</div>
				</div>
				<div class="px-6 pb-6">
					<PasswordsTable :passwords="passwords" />
				</div>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta } from '#imports'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import GearIcon from '~/assets/svg/Monochrome=gearshape.fill.svg'
import EditIcon from '~/assets/svg/Monochrome=applepencil.gen1.svg'
import PasswordsTable from '~/components/account/PasswordsTable.vue'

definePageMeta({ middleware: 'auth', layout: 'account' })

const passwords = [
	{ name: 'Email', icon: { type: 'letter', content: 'E' }, login: 'test', starred: false },
	{ name: 'SSH', icon: { type: 'icones', content: 'material-symbols:terminal-rounded' }, login: 'root', starred: false },
	{ name: 'База данных', icon: { type: 'icones', content: 'material-symbols:database' }, login: 'root', starred: true },
	{ name: 'Apple ID', icon: { type: 'image', content: 'https://www.apple.com/favicon.ico' }, login: 'test@example.com' },
	{ name: 'OdekolonShop', icon: { type: 'image', content: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }, login: 'test@example.com', starred: true },
	{ name: 'Panteric', icon: { type: 'image', content: 'https://panteric.ru/files/gallery/58/mstile_144x144/favikon_1521468371_1645088044.png' }, login: 'admin', starred: false },
]

</script>

<style scoped lang="scss">

.add-password-btn {

}

.page-tile {
	box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.06);
}

</style>

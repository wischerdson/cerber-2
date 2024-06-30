<template>
	<AbstractList name="Доступы" add-item-title="Добавить доступ">
		<ul v-if="false">
			<SecretItem name="База данных" :icon="{ type: 'icones', value: 'material-symbols:database' }" />
			<SecretItem name="SSH" :icon="{ type: 'icones', value: 'material-symbols:terminal-rounded' }" />
			<SecretItem name="Админка" />
			<SecretItem name="panteric" :icon="{ type: 'img', value: 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png' }" />
			<SecretItem name="odekolon" :actual="false" :icon="{ type: 'img', value: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }" />
			<SecretItem name="Apple" :icon="{ type: 'img', value: 'https://www.apple.com/favicon.ico' }" />
			<SecretItem name="ftp" :icon="{ type: 'icones', value: 'mdi:folder-network-outline' }" />
		</ul>
		<div class="text-center">
			<div class="text-sm text-gray-500">Ни одного доступа еще нет</div>
			<TheClickable class="h-8 rounded-full bg-slate-800 text-white px-5 mt-3 text-sm inline-flex items-center mx-auto">Добавить</TheClickable>
		</div>
	</AbstractList>
</template>

<script setup lang="ts">

import SecretItem from '~/components/account/secrets/list/SecretItem.vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import TheClickable from '~/components/ui/Clickable.vue'

</script>

<template>
	<AbstractList name="Группы" :showOnInit="false" add-item-title="Создать группу">
		<ul v-if="false">
			<GroupItem name="База данных" :icon="{ type: 'icones', value: 'material-symbols:database' }" />
			<GroupItem name="SSH" :icon="{ type: 'icones', value: 'material-symbols:terminal-rounded' }" />
			<GroupItem name="Админка" />
			<GroupItem name="panteric" :icon="{ type: 'img', value: 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png' }" />
			<GroupItem name="odekolon" :actual="false" :icon="{ type: 'img', value: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }" />
			<GroupItem name="Apple" :icon="{ type: 'img', value: 'https://www.apple.com/favicon.ico' }" />
			<GroupItem name="ftp" :icon="{ type: 'icones', value: 'mdi:folder-network-outline' }" />
		</ul>
		<div class="text-center" v-else>
			<div class="text-sm text-gray-500">Группы отсутствуют</div>
			<UiClickable class="h-7 rounded-full bg-slate-900 px-5 mt-3 inline-flex items-center mx-auto">
				<span class="text-white text-sm font-medium">Добавить</span>
			</UiClickable>
		</div>
	</AbstractList>
</template>

<script setup lang="ts">

import GroupItem from '~/components/account/secrets/list/GroupItem.vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import UiClickable from '~/components/ui/Clickable.vue'

</script>

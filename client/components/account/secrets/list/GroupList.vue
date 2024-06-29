<template>
	<AbstractList name="Группы">
		<ul>
			<GroupItem name="База данных" :icon="{ type: 'icones', value: 'material-symbols:database' }" />
			<GroupItem name="SSH" :icon="{ type: 'icones', value: 'material-symbols:terminal-rounded' }" />
			<GroupItem name="Админка" />
			<GroupItem name="panteric" :icon="{ type: 'img', value: 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png' }" />
			<GroupItem name="odekolon" :actual="false" :icon="{ type: 'img', value: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }" />
			<GroupItem name="Apple" :icon="{ type: 'img', value: 'https://www.apple.com/favicon.ico' }" />
			<GroupItem name="ftp" :icon="{ type: 'icones', value: 'mdi:folder-network-outline' }" />
		</ul>
	</AbstractList>
</template>

<script setup lang="ts">

import GroupItem from '~/components/account/secrets/list/GroupItem.vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'

</script>

<template>
	<AbstractList name="Доступы">
		<ul>
			<SecretItem name="База данных" :icon="{ type: 'icones', value: 'material-symbols:database' }" />
			<SecretItem name="SSH" :icon="{ type: 'icones', value: 'material-symbols:terminal-rounded' }" />
			<SecretItem name="Админка" />
			<SecretItem name="panteric" :icon="{ type: 'img', value: 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png' }" />
			<SecretItem name="odekolon" :actual="false" :icon="{ type: 'img', value: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }" />
			<SecretItem name="Apple" :icon="{ type: 'img', value: 'https://www.apple.com/favicon.ico' }" />
			<SecretItem name="ftp" :icon="{ type: 'icones', value: 'mdi:folder-network-outline' }" />
		</ul>
	</AbstractList>
</template>

<script setup lang="ts">

import SecretItem from '~/components/account/secrets/list/SecretItem.vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'

</script>

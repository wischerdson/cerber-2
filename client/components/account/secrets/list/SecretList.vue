<template>
	<AbstractList name="Доступы" add-item-title="Добавить доступ" @add="secretsStore.setModeCreate">
		<ul class="secret-list" v-if="true">
			<SecretItem
				v-for="secret in secrets"
				:secret="secret"
				:icon="{ type: 'letter' }"
				:key="secret.clientCode"
			/>
			<!-- <SecretItem name="SSH" :icon="{ type: 'icones', value: 'material-symbols:terminal-rounded' }" @click="secretsStore.setModeView" />
			<SecretItem name="Админка" @click="secretsStore.setModeView" />
			<SecretItem name="panteric" :icon="{ type: 'img', value: 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png' }" @click="secretsStore.setModeView" />
			<SecretItem name="odekolon" :actual="false" :icon="{ type: 'img', value: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }" @click="secretsStore.setModeView" />
			<SecretItem name="Apple" :icon="{ type: 'img', value: 'https://www.apple.com/favicon.ico' }" @click="secretsStore.setModeView" />
			<SecretItem name="ftp" :icon="{ type: 'icones', value: 'mdi:folder-network-outline' }" @click="secretsStore.setModeView" /> -->
		</ul>
		<div class="text-center" v-else>
			<div class="text-sm text-gray-500">Ни одного доступа еще нет</div>
			<TheClickable class="h-7 rounded-full bg-slate-900 px-5 mt-3 inline-flex items-center mx-auto" @click="secretsStore.setModeCreate">
				<span class="text-white text-sm font-medium">Добавить</span>
			</TheClickable>
		</div>
	</AbstractList>
</template>

<script setup lang="ts">

import SecretItem from '~/components/account/secrets/list/SecretItem.vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import { useSecretsStore, type Secret } from '~/store/secrets'

interface SecretListProps {
	secrets: Secret[]
}

defineProps<SecretListProps>()

const secretsStore = useSecretsStore()

</script>

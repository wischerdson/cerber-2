<template>
	<AbstractList class="mt-4" name="Доступы" :showOnInit="true" add-item-title="Создать новый доступ">
		<ul class="-mx-4">
			<li>
				<SecretItem :secret="{ name: 'FTP' }" :icon="['letter', 'FTP']" />
			</li>
			<li>
				<SecretItem :secret="{ name: 'Wildberries' }" :icon="['img', 'https://www.wildberries.ru/favicon.ico']" />
			</li>
			<li>
				<SecretItem :secret="{ name: 'SSH' }" :icon="['letter', 'SSH']" />
			</li>
			<li>
				<SecretItem :secret="{ name: 'Дзен' }" :icon="['img', 'https://dzen.ru/favicon.ico']" />
			</li>
		</ul>
	</AbstractList>
</template>

<script setup lang="ts">

import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretItem from '~/components/account/secrets/list/SecretItem.vue'

</script>

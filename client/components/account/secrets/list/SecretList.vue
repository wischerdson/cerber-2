<template>
	<AbstractList class="mt-4" name="Доступы" :showOnInit="true" add-item-title="Создать новый доступ">
		<ul class="-mx-4">
			<li v-for="secret in secrets">
				<SecretItem :secret="secret" :icon="['letter', 'FTP']" />
			</li>
		</ul>
	</AbstractList>
</template>

<script setup lang="ts">

import { computed } from 'vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretItem from '~/components/account/secrets/list/SecretItem.vue'
import { useSecretsStore } from '~/store/secrets'

const secretsStore = useSecretsStore()

const secrets = computed(() => secretsStore.secrets)

</script>

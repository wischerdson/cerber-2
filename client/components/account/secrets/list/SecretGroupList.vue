<template>
	<AbstractList name="Группы" :showOnInit="true" add-item-title="Добавить группу">
		<ul class="-mx-4">
			<li>
				<SecretGroupItem name="Ozon" />
			</li>
			<li>
				<SecretGroupItem name="Wildberries" />
			</li>
		</ul>
	</AbstractList>
</template>

<script setup lang="ts">

import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretGroupItem from '~/components/account/secrets/list/SecretGroupItem.vue'

</script>

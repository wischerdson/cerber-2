<template>
	<AbstractList name="Группы" :showOnInit="true" add-item-title="Добавить группу" @add="addGroup">
		<TransitionGroup
			class="-mx-4"
			tag="ul"
			name="group-list"
		>
			<li v-for="group in groups" :key="group.clientCode">
				<SecretGroupItem :name="group.name" :edit-mode="!('id' in group)" @save-name="createGroup" />
			</li>
			<li>
				<SecretGroupItem name="Ozon" />
			</li>
			<li>
				<SecretGroupItem name="Wildberries" />
			</li>
		</TransitionGroup>
	</AbstractList>
</template>

<script setup lang="ts">

import { uid } from '~/utils/helpers'
import { ref } from 'vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretGroupItem from '~/components/account/secrets/list/groups/SecretGroupItem.vue'
import { useSecretGroupsStore } from '~/store/secret-groups'
import type { SecretGroup, SecretGroupForCreate } from '~/repositories/adapters/secret-group-adapter'

const store = useSecretGroupsStore()

const groups = ref<(SecretGroup | SecretGroupForCreate)[]>(await store.fetch(null))

const addGroup = () => groups.value.unshift({ name: 'Новая группа', clientCode: uid(), description: null, parentId: null })

const createGroup = async (name: string) => {
	groups.value[0].name = name
	groups.value[0] = await store.create(groups.value[0])
}

</script>

<style lang="scss" scoped>

.group-list-move,
.group-list-enter-active,
.group-list-leave-active {
	transition: transform .3s ease, opacity .3s ease;
}

.group-list-leave-active {
	position: absolute;
	width: 100%;
}

.group-list-enter-from,
.group-list-leave-to {
	opacity: 0;
	transform: scale(.8);
}

</style>

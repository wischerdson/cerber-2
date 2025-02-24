<template>
	<AbstractList name="Группы" :showOnInit="true" add-item-title="Добавить группу" @add="store.addNew()">
		<TransitionGroup
			class="-mx-4"
			tag="ul"
			name="group-list"
			v-if="groups.length"
		>
			<li v-for="group in groups" :key="group.clientCode">
				<SecretGroupItem :name="group.name" :edit-mode="('editMode' in group) && group.editMode" @save-name="createGroup" />
			</li>
		</TransitionGroup>
	</AbstractList>
</template>

<script setup lang="ts">

import { computed } from 'vue'
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretGroupItem from '~/components/account/secrets/list/groups/SecretGroupItem.vue'
import { useSecretGroupsStore } from '~/store/secret-groups'

const store = useSecretGroupsStore()
const groups = computed(() => store.groups)

const createGroup = async (name: string) => {
	const localGroup = groups.value[0]

	if ('editMode' in localGroup) {
		localGroup.name = name
		localGroup.editMode = false
		await store.create(groups.value[0])
	}
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

<template>
	<AbstractList name="Группы" :showOnInit="true" add-item-title="Добавить группу" @add="addNew()">
		<TransitionGroup
			class="-mx-4"
			tag="ul"
			name="group-list"
		>
			<li v-for="group in groups" :key="'clientCode' in group ? group.clientCode : group.alias">
				<SecretGroupItem :group="group" @save-name="saveNewName" />
			</li>
		</TransitionGroup>
		<div v-if="!groups.length">
			<p class="text-center text-gray-400 text-sm">Группы отсутствуют</p>
		</div>
	</AbstractList>
</template>

<script setup lang="ts">

import { computed } from 'vue'
import { uid } from '#imports'
import AbstractList from '~/components/account/nodes/list/AbstractList.vue'
import SecretGroupItem from '~/components/account/nodes/list/groups/GroupItem.vue'
import { useNodesStore } from '~/store/nodes'
import type { Group, NewGroup } from '~/repositories/adapters/node-adapter'

const store = useNodesStore()
const groups = computed(() => store.groups)

const addNew = () => {
	store.nodes.unshift({
		name: 'Новая группа',
		type: 'group',
		fields: [],
		notes: null,
		clientCode: uid(),
		editMode: true
	})
}

const saveNewName = (group: Group | NewGroup, newName: string) => {
	group.name = newName
	group.editMode = false

	'id' in group ? store.update(group as Group) : store.create(group as NewGroup)
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

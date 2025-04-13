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
import AbstractList from '~/components/account/secrets/list/AbstractList.vue'
import SecretGroupItem from '~/components/account/secrets/list/groups/SecretGroupItem.vue'
import { useDocumentsStore, type NewSecretGroup, type SecretGroup } from '~/store/documents'
import { uid } from '#imports'

const store = useDocumentsStore()
const groups = computed(() => store.groups)

const addNew = () => {
	store.documents.unshift({
		name: 'Новая группа',
		isGroup: true,
		fields: [],
		notes: null,
		clientCode: uid(),
		editMode: true
	})
}

const saveNewName = (group: SecretGroup | NewSecretGroup, newName: string) => {
	group.name = newName
	group.editMode = false

	'id' in group ? store.update(group as SecretGroup) : store.create(group as NewSecretGroup)
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

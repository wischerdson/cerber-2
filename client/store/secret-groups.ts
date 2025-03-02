import { defineStore } from 'pinia'
import type { SecretGroup, SecretGroupForCreate } from '~/repositories/adapters/secret-group-adapter'
import { createGroup, getGroups } from '~/repositories/secret-groups'
import { ref } from 'vue'
import { uid } from '#imports'

export const useSecretGroupsStore = defineStore('secret-groups', () => {
	const groups = ref<(SecretGroup | SecretGroupForCreate & { editMode: boolean })[]>([])
	const current = ref<SecretGroup>()

	const addNew = () => {
		groups.value.unshift({
			name: 'Новая группа',
			clientCode: uid(),
			description: null,
			parentId: null,
			editMode: true
		})
	}

	const create = async (data: SecretGroupForCreate) => {
		groups.value[0] = await createGroup(data)
		groups.value[0].clientCode = data.clientCode
		groups.value = (groups.value as SecretGroup[]).sort((g1, g2) => g1.id - g2.id)
	}

	const fetch = async (parentGroupId: number|null = null) => {
		return groups.value = await getGroups()
	}

	const set = (_groups: SecretGroup[]) => groups.value = _groups

	const setCurrent = (_group: SecretGroup) => current.value = _group

	return {
		groups,
		fetch, create, addNew, set, setCurrent
	}
})

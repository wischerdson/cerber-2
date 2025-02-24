import { defineStore } from 'pinia'
import type { SecretGroup, SecretGroupForCreate } from '~/repositories/adapters/secret-group-adapter'
import { createGroup, getGroups } from '~/repositories/secret-groups'
import { ref } from 'vue'

export const useSecretGroupsStore = defineStore('secret-groups', () => {
	const groups = ref<SecretGroup[]>([])

	const create = async (data: SecretGroupForCreate) => {
		return { ...await createGroup(data), clientCode: data.clientCode }
	}

	const fetch = async (spaceId: number | null, parentGroupId?: number) => {
		return groups.value = await getGroups()
	}

	return { fetch, create }
})

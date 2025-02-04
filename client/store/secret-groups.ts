import { defineStore } from 'pinia'
import type { SecretGroupForCreate } from '~/repositories/adapters/secret-group-adapter'
import { createGroup } from '~/repositories/secret-groups'

export const useSecretGroupsStore = defineStore('secret-groups', () => {
	const create = async (data: SecretGroupForCreate) => {
		const group = await createGroup(data)

		console.log(group)
	}

	const fetch = (spaceId: number | null, parentGroupId?: number) => {

	}

	return { fetch, create }
})

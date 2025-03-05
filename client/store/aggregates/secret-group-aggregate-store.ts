import { defineStore } from 'pinia'
import { fetchSecretGroupAggregate } from '~/repositories/aggregates/secret-group-aggregate'
import { useSecretGroupsStore } from '~/store/secret-groups'
import { useBreadcrumbStore, type BreadcrumbChain } from '~/store/breadcrumb'
import { useSecretsStore } from '~/store/secrets'
import type { SecretGroup } from '~/repositories/adapters/secret-group-adapter'

export const useSecretGroupAggregateStore = defineStore('secret-group-aggregate', () => {
	const groupsToBreadcrumbChain = (groups: SecretGroup[]): BreadcrumbChain => {
		return groups.map(g => ({
			name: g.name,
			link: { to: { name: 'group-alias', params: { alias: g.alias } } }
		}))
	}

	const fetch = async (parentGroup: { alias?: string|null, id?: number|null }) => {
		const aggregate = await fetchSecretGroupAggregate(parentGroup)

		const secretGroupsStore = useSecretGroupsStore()

		secretGroupsStore.set(aggregate.childrenGroups)
		secretGroupsStore.setCurrent(aggregate.currentGroup)

		useSecretsStore().set(aggregate.secrets)
		useBreadcrumbStore().setChain(
			groupsToBreadcrumbChain(aggregate.parentGroups)
		)
	}

	return { fetch }
})

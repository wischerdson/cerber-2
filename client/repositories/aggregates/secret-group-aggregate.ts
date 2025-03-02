import { useGetReq } from '~/composables/use-request'
import { serverToClientSecretGroupAggregate, type ServerSecretGroupAggregate } from '~/repositories/adapters/secret-group-aggregate-adapter'

export const fetchSecretGroupAggregate = async (parentGroup: { alias?: string, id?: number }) => {
	let query: object

	if (parentGroup.alias) {
		query = { group_alias: parentGroup.alias }
	} else if (parentGroup.id) {
		query = { group_id: parentGroup.id }
	} else {
		throw new Error('Either alias or ID must be passed in the "parentGroup" object.')
	}

	const aggregate = await useGetReq<ServerSecretGroupAggregate>('/aggregates/secret-group', { query })
		.sign().shouldEncrypt().send()

	return serverToClientSecretGroupAggregate(aggregate)
}

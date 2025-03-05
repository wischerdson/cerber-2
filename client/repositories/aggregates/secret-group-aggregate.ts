import { useGetReq } from '~/composables/use-request'
import { serverToClientSecretGroupAggregate, type ServerSecretGroupAggregate } from '~/repositories/adapters/secret-group-aggregate-adapter'

export const fetchSecretGroupAggregate = async (parentGroup: { alias?: string|null, id?: number|null }) => {
	let query: object

	if (typeof parentGroup.alias !== 'undefined') {
		query = { group_alias: parentGroup.alias }
	} else if (typeof parentGroup.id !== 'undefined') {
		query = { group_id: parentGroup.id }
	} else {
		throw new Error('Either alias or ID must be passed in the "parentGroup" object.')
	}

	console.log(query)

	const aggregate = await useGetReq<ServerSecretGroupAggregate>('/aggregates/secret-group', { query })
		.sign().shouldEncrypt().send()

	return serverToClientSecretGroupAggregate(aggregate)
}

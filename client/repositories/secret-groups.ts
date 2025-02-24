import { useGetReq, usePostReq } from '~/composables/use-request'
import { clientToServerSecretGroupForCreate, serverToClientSecretGroup, type SecretGroupForCreate, type ServerSecretGroup } from './adapters/secret-group-adapter'

export const getGroups = async () => {
	const groups = await useGetReq<ServerSecretGroup[]>('/secret-groups').sign().shouldEncrypt().send()

	return groups.map(group => serverToClientSecretGroup(group))
}

export const createGroup = async (data: SecretGroupForCreate) => {
	const group = await usePostReq<ServerSecretGroup>(
		'/secret-groups',
		clientToServerSecretGroupForCreate(data)
	).sign().shouldEncrypt().send()

	return serverToClientSecretGroup(group)
}

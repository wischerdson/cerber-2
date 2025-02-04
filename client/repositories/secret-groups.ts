import { useGetReq, usePostReq } from '~/composables/use-request'
import { clientToServerSecretGroupForCreate, serverToClientSecretGroup, type SecretGroupForCreate, type ServerSecretGroup } from './adapters/secret-group-adapter'

export const getGroups = async () => {
	const groups = await useGetReq('/groups').sign().shouldEncrypt().send()
}

export const createGroup = async (data: SecretGroupForCreate) => {
	const group = await usePostReq<ServerSecretGroup>(
		'/groups',
		clientToServerSecretGroupForCreate(data)
	).sign().shouldEncrypt().send()

	return serverToClientSecretGroup(group)
}

import { useGetReq, usePostReq } from '~/composables/use-request'
import { clientToServerSecretGroupForCreate, serverToClientSecretGroup, type SecretGroupForCreate, type ServerSecretGroup } from './adapters/secret-group-adapter'
import { auth } from '~/decorators/request/auth.decorator'
import { encrypt } from '~/decorators/request/encryption.decorator'

export const getGroups = async () => {
	const groups = await useGetReq<ServerSecretGroup[]>('/secret-groups')
		.apply(auth, encrypt)
		.send()

	return groups.map(group => serverToClientSecretGroup(group))
}

export const createGroup = async (data: SecretGroupForCreate) => {
	const group = await usePostReq<ServerSecretGroup>()
		.url('/secret-groups')
		.body(clientToServerSecretGroupForCreate(data))
		.apply(auth, encrypt)
		.send()

	return serverToClientSecretGroup(group)
}

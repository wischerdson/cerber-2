import type { SecretForCreate, ServerSecret, ServerSecretPreview } from './adapters/secret-adapter'
import { clientToServerSecretForCreate, serverToClientSecret, serverToClientSecretPreview } from './adapters/secret-adapter'
import { useGetReq, usePostReq } from '~/composables/use-request'

export const createSecret = (secret: SecretForCreate) => {
	return usePostReq(
		'/secrets', clientToServerSecretForCreate(secret)
	).sign().shouldEncrypt().send()
}

export const fetchSecrets = async () => {
	const serverSecrets = await useGetReq<ServerSecretPreview[]>('/secrets').sign().send()
	const secrets = serverSecrets.map(s => serverToClientSecretPreview(s))

	return secrets
}

export const fetchSecretDetails = async (id: number) => {
	const secret = await useGetReq<ServerSecret>(`/secrets/${id}`).sign().send()

	return serverToClientSecret(secret)
}

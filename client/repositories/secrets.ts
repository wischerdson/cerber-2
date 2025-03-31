import type { SecretForCreate, ServerSecret, ServerSecretPreview } from './adapters/secret-adapter'
import { auth } from '~/utils/decorators/request/auth.decorator'
import { clientToServerSecretForCreate, serverToClientSecret, serverToClientSecretPreview } from './adapters/secret-adapter'
import { useGetReq, usePostReq } from '~/composables/use-request'
import { encrypt } from '~/utils/decorators/request/encryption.decorator'

export const createSecret = (secret: SecretForCreate) => {
	return usePostReq('/secrets')
		.body(clientToServerSecretForCreate(secret))
		.apply(auth, encrypt)
		.send()
}

export const fetchSecrets = async () => {
	const serverSecrets = await useGetReq<ServerSecretPreview[]>('/secrets')
		.apply(auth)
		.send()

	return serverSecrets.map(s => serverToClientSecretPreview(s))
}

export const fetchSecretDetails = async (id: number) => {
	const secret = await useGetReq<ServerSecret>(`/secrets/${id}`)
		.apply(auth)
		.send()

	return serverToClientSecret(secret)
}

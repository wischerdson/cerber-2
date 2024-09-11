import type { SecretForCreate, ServerSecret } from './adapters/secret-adapter'
import { clientToServerSecretForCreate, serverToClientSecret } from './adapters/secret-adapter'
import { useGetReq, usePostReq } from '~/composables/use-request'

export const createSecret = (secret: SecretForCreate) => {
	return usePostReq(
		'/secrets', clientToServerSecretForCreate(secret)
	).sign().shouldEncrypt().send()
}

export const fetchSecrets = async () => {
	const serverSecrets = await useGetReq<ServerSecret[]>('/secrets').sign().send();
	const secrets = serverSecrets.map(s => serverToClientSecret(s))

	return secrets
}

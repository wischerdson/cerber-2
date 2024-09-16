import { serverToClientUser, type ServerUser } from './adapters/user-adapter'
import { useGetReq, usePostReq } from '~/composables/use-request'

export const fetchUser = async () => {
	return serverToClientUser(
		await useGetReq<ServerUser>('/auth/user')
			.sign()
			.send()
	)
}

export const initEncryptionHandshake = (clientPublicKey: string) => {
	return usePostReq<{ id: string, server_public_key: string }>('/handshake', clientPublicKey)
		.setHeader('Content-Type', 'text/plain')
		.send()
}

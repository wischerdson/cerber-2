import { serverToClientUser, type ServerUser } from './adapters/user-adapter'
import { useGetReq } from '~/composables/use-request'
import { makeRequest } from '~/utils/request'

export const fetchUser = async () => {
	return serverToClientUser(
		await useGetReq<ServerUser>('/auth/user')
			.sign()
			.send()
	)
}

export const initEncryptionHandshake = (clientPublicKey: string) => {
	return makeRequest<{ id: string, server_public_key: string }>('/handshake', {
		method: 'POST',
		body: clientPublicKey
	}).setHeader('Content-Type', 'text/plain').send()
}

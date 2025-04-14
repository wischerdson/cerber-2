import { useGetReq, usePostReq } from '#imports'
import { auth } from '~/utils/decorators/request/auth.decorator'
import { encrypt } from '~/utils/decorators/request/encryption.decorator'
import { transformNewNodeToServer, transformNodeToClient, type NewNode, type ServerNode } from './adapters/node-adapter'

export const createNode = async (newNode: NewNode) => {
	const node = await usePostReq<ServerNode>()
		.url('/nodes')
		.body(transformNewNodeToServer(newNode))
		.apply(auth, encrypt)
		.send()

	return transformNodeToClient(node)
}

export const getRootNodes = async () => {
	const nodes = await useGetReq<ServerNode[]>()
		.url('/nodes')
		.apply(auth, encrypt)
		.send()

	return nodes.map(transformNodeToClient)
}

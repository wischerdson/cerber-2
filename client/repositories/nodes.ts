import { useGetReq, usePostReq } from '#imports'
import { auth } from '~/utils/decorators/request/auth.decorator'
import { encrypt } from '~/utils/decorators/request/encryption.decorator'
import { transformNewNodeToServer, transformNodeToClient } from './adapters/node-adapter'

export const createNode = async (newNode: App.Nodes.NewNode) => {
	const node = await usePostReq<App.Nodes.Server.Node>()
		.url('/nodes')
		.body(transformNewNodeToServer(newNode))
		.apply(auth, encrypt)
		.send()

	return transformNodeToClient(node)
}

export const getRootNodes = async () => {
	const nodes = await useGetReq<App.Nodes.Server.Node[]>()
		.url('/nodes')
		.apply(auth, encrypt)
		.send()

	return nodes.map(transformNodeToClient)
}

export const getNodes = async () => {
	const nodes = await useGetReq<App.Nodes.Server.Node[]>()
}

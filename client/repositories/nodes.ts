import { useGetReq, usePostReq, usePutReq } from '#imports'
import { auth } from '~/utils/decorators/request/auth.decorator'
import { encrypt } from '~/utils/decorators/request/encryption.decorator'
import { transformCreatedNodeToClient, transformNewNodeToServer, transformNodeForUpdateToServer, transformNodeResourceToClient, transformNodeToClient } from './adapters/node-adapter'

export const createNode = async (newNode: Dto.Nodes.NewNode) => {
	const node = await usePostReq<Dto.Nodes.Server.Node>()
		.url('/nodes')
		.body(transformNewNodeToServer(newNode))
		.apply(auth, encrypt)
		.send()

	return transformNodeToClient(node)
}

export const createNodesBatch = async (newNodes: Dto.Nodes.NewNode[]) => {
	const newNodesForServer = newNodes.map(transformNewNodeToServer)

	const nodes = await usePostReq<Dto.Nodes.Server.CreatedNode[]>()
		.url('/nodes/batch')
		.body(newNodesForServer)
		.apply(auth, encrypt)
		.send()

	return nodes.map(transformCreatedNodeToClient)
}

export const updateNodesBatch = async (nodesForUpdate: Dto.Nodes.Node[]) => {
	const nodesToUpdateForServer = nodesForUpdate.map(transformNodeForUpdateToServer)

	await usePutReq('/nodes/batch')
		.body(nodesToUpdateForServer)
		.apply(auth, encrypt)
		.send()
}

export const getRootNodes = async () => {
	const resource = await useGetReq<Dto.Nodes.Server.NodeResource>()
		.url('/nodes')
		.apply(auth, encrypt)
		.send()

	return transformNodeResourceToClient(resource)
}

export const getNodesByChain = async (chain: string[]) => {
	const resource = await useGetReq<Dto.Nodes.Server.NodeResource>()
		.url('/nodes')
		.query({ chain: chain.join(',') })
		.apply(auth, encrypt)
		.send()

	return transformNodeResourceToClient(resource)
}

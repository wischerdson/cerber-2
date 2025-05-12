import { defineStore } from 'pinia'
import { createNodesBatch, getNodesByChain, getRootNodes, updateNodesBatch } from '~/repositories/nodes'
import { computed, ref } from 'vue'

export const useNodeStore = defineStore('nodes', () => {
	const nodes = ref<(Dto.Nodes.Node | Dto.Nodes.NewNode)[]>([])
	const parents = ref<Dto.Nodes.Node[]>([])
	const current = ref<Dto.Nodes.Node | null>(null)

	const fetchRootNodes = async () => {
		const { descendants } = await getRootNodes()

		parents.value = []
		nodes.value = descendants
		current.value = null
	}

	const fetchNodesByChain = async (chain: string[]) => {
		const resource = await getNodesByChain(chain)

		parents.value = resource.parents
		nodes.value = resource.descendants
		current.value = resource.current
	}

	const create = async (nodesForCreate: Dto.Nodes.NewNode[]) => {
		const createdNodes = await createNodesBatch(nodesForCreate)

		nodes.value = nodes.value.map(n => {
			if ('clientCode' in n) {
				const createdNode = createdNodes.find(cN => cN.clientCode === n.clientCode)

				if (createdNode) {
					return createdNode
				}
			}

			return n
		})
	}

	const add = (newNode: Dto.Nodes.NewNode) => {
		nodes.value.unshift(newNode)
	}

	const update = async (nodesForUpdate: Dto.Nodes.Node[]) => {
		await updateNodesBatch(nodesForUpdate)

		nodes.value = nodes.value.map(n => {
			if ('id' in n && n.changedOnClient) {
				const updatedNode = nodesForUpdate.find(uN => uN.id === n.id)

				if (updatedNode) {
					updatedNode.changedOnClient = false

					return updatedNode
				}
			}

			return n
		})
	}

	const change = (nodeForChange: Dto.Nodes.Node | Dto.Nodes.NewNode) => {
		if ('id' in nodeForChange) {
			nodeForChange.changedOnClient = true
		}

		nodes.value = nodes.value.map(n => {
			if ('id' in n && 'id' in nodeForChange) {
				return nodeForChange.id === n.id ? nodeForChange : n
			}

			if ('clientCode' in n && 'clientCode' in nodeForChange) {
				return nodeForChange.clientCode === n.clientCode ? nodeForChange : n
			}

			return n
		})
	}

	const sync = async () => {
		const nodesForCreate: Dto.Nodes.NewNode[] = []
		const nodesForUpdate: Dto.Nodes.Node[] = []

		nodes.value.forEach(n => {
			if ('id' in n && n.changedOnClient) {
				nodesForUpdate.push(n)
			}

			if (!('id' in n)) {
				nodesForCreate.push(n)
			}
		})

		nodesForCreate.length && await create(nodesForCreate)
		nodesForUpdate.length && await update(nodesForUpdate)
	}

	return {
		nodes: computed(() => nodes.value),
		current: computed(() => current.value),
		parents: computed(() => parents.value),
		fetchRootNodes, fetchNodesByChain, add, create, change, update, sync
	}
})

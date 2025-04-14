import { defineStore } from 'pinia'
import { createNode, getRootNodes } from '~/repositories/nodes'
import { computed, ref } from 'vue'
import type { Document, Group, NewDocument, NewGroup, NewNode, Node } from '~/repositories/adapters/node-adapter'

export const useNodesStore = defineStore('nodes', () => {
	const nodes = ref<(Node | NewNode | NewGroup)[]>([])

	const groups = computed(() => {
		return nodes.value.filter(d => d.type === 'group') as (Group | NewGroup)[]
	})
	const documents = computed(() => {
		return nodes.value.filter(n => n.type === 'document') as (Document | NewDocument)[]
	})

	const fetchRootNodes = async () => {
		nodes.value = await getRootNodes()
	}

	const create = async (newNode: NewNode) => {
		const node = await createNode(newNode)

		nodes.value = nodes.value.map(_node => {
			if ('clientCode' in _node && _node.clientCode === newNode.clientCode) {
				node.clientCode = _node.clientCode

				return node
			}

			return _node
		})
	}

	const update = (node: Node) => {
		// groups.value = groups.value.map(group => groups.value.find(g => g.id === document.id) || group);
		// groups.find((g: Document) => document.id == g.id)
	}

	return {
		groups, documents, nodes,
		fetchRootNodes, create, update
	}
})

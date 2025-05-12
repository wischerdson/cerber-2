import { defineStore } from 'pinia'
import { useNodeStore } from './nodes'
import { computed } from 'vue'
import { uid } from '~/utils/helpers'

export const useGroupsStore = defineStore('groups', () => {
	const nodesStore = useNodeStore()

	const groups = computed(() => {
		return nodesStore.nodes.filter(d => d.type === 'group') as (Dto.Nodes.Group | Dto.Nodes.NewGroup)[]
	})

	const create = () => nodesStore.add({
		name: 'Новая группа',
		type: 'group',
		notes: null,
		clientCode: uid(),
		editMode: true,
		parentId: nodesStore.current ? nodesStore.current.id : null
	})

	const update = async (group: Dto.Nodes.Group | Dto.Nodes.NewGroup) => {
		nodesStore.change(group)
		await nodesStore.sync()
	}

	return {
		groups,
		create, update
	}
})

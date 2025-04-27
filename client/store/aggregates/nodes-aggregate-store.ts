import { defineStore } from 'pinia'
import { fetchDocumentGroupAggregate } from '~/repositories/aggregates/document-group-aggregate'
import { useNodesStore } from '~/store/nodes'

export const useNodesAggregateStore = defineStore('nodes-aggregate', () => {
	// const distribute = (aggregate: DocumentGroupAggregate) => {
	// 	const store = useNodesStore()

	// 	store.nodes = aggregate.descendants
	// }

	// const fetchByParentId = async (parentId: number|null) => {
	// 	distribute(
	// 		await fetchDocumentGroupAggregate({ id: parentId })
	// 	)
	// }

	// const fetchByParentAlias = async (parentAlias: string|null) => {
	// 	distribute(
	// 		await fetchDocumentGroupAggregate({ alias: parentAlias })
	// 	)
	// }

	// return { fetchByParentId, fetchByParentAlias }
})

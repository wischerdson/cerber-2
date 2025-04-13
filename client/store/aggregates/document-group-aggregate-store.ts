import { defineStore } from 'pinia'
import type { DocumentGroupAggregate } from '~/repositories/adapters/document-group-aggregate-adapter'
import { fetchDocumentGroupAggregate } from '~/repositories/aggregates/document-group-aggregate'
import { useDocumentsStore } from '~/store/documents'

export const useDocumentGroupAggregateStore = defineStore('document-group-aggregate', () => {
	const distribute = (aggregate: DocumentGroupAggregate) => {
		const store = useDocumentsStore()

		store.documents = aggregate.descendants
	}

	const fetchByParentId = async (parentId: number|null) => {
		distribute(
			await fetchDocumentGroupAggregate({ id: parentId })
		)
	}

	const fetchByParentAlias = async (parentAlias: string|null) => {
		distribute(
			await fetchDocumentGroupAggregate({ alias: parentAlias })
		)
	}

	return { fetchByParentId, fetchByParentAlias }
})

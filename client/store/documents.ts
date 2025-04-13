import type { NewDocument, Document } from '~/repositories/adapters/document-adapter'
import { defineStore } from 'pinia'
import { createDocument } from '~/repositories/documents'
import { computed, ref } from 'vue'

export type SecretGroup = Document & { isGroup: true, clientCode?: string, editMode?: boolean }

export type NewSecretGroup = NewDocument & { isGroup: true, editMode: boolean }

export const useDocumentsStore = defineStore('documents', () => {
	const documents = ref<(Document | NewDocument | SecretGroup | NewDocument)[]>([])

	const groups = computed(() => {
		return documents.value.filter(d => d.isGroup) as (SecretGroup | NewSecretGroup)[]
	})
	const secrets: unknown[] = []

	const create = async (newDocument: NewDocument) => {
		const document = await createDocument(newDocument)

		documents.value = documents.value.map(d => {
			if ('clientCode' in d && d.clientCode === newDocument.clientCode) {
				document.clientCode = d.clientCode

				return document
			}

			return d
		})
	}

	const update = (document: Document) => {
		// groups.value = groups.value.map(group => groups.value.find(g => g.id === document.id) || group);
		// groups.find((g: Document) => document.id == g.id)
	}

	return {
		groups, secrets, documents,
		create, update
	}
})

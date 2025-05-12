declare namespace Dto.Nodes {
	interface Node {
		id: number
		type: 'document' | 'group' | 'link'
		alias: string
		name: string
		notes: string|null
		isEffective: boolean
		clientCode?: string
		changedOnClient: boolean
		createdAt: Date
		deletedAt: Date|null
	}

	interface NodeResource {
		current: Node | null
		parents: Node[]
		descendants: Node[]
	}

	interface Document extends Node {
		type: 'document'
		fields?: DocumentField[]
	}

	interface DocumentField {
		id: number
		label: string
		shortDescription: string | null
		value: string
		isMultiline: boolean
		isSecure: boolean
		sort: number
	}

	interface Group extends Node {
		type: 'group'
		editMode: boolean
	}

	interface NewNode {
		name: string
		notes: string|null
		type: 'document' | 'group' | 'link'
		clientCode: string
		parentId: number | null
		editMode?: boolean
	}

	interface NewDocument extends NewNode {
		type: 'document'
		fields: NewDocumentField[]
	}

	interface NewDocumentField {
		label: string
		shortDescription: string | null
		value: string
		isMultiline: boolean
		isSecure: boolean
		sort: number
		clientCode: string
	}

	interface NewGroup extends NewNode {
		type: 'group'
		editMode: boolean
	}

	interface CreatedNode extends Node {
		clientCode: string
	}

	namespace Server {
		interface Node {
			id: number
			type: 'document' | 'group' | 'link'
			alias: string
			name: string
			notes: string | null
			is_effective: boolean
			created_at: number
			deleted_at: number | null
		}

		interface NodeResource {
			current: Node | null
			parents: Node[]
			descendants: Node[]
		}

		interface Document extends Node {
			type: 'document'
			fields?: DocumentField[]
		}

		interface DocumentField {
			id: number
			label: string
			short_description: string | null
			value: string
			is_multiline: boolean
			is_secure: boolean
			sort: number
		}

		interface Group extends Node {
			type: 'group'
		}

		interface NewNode {
			name: string
			notes: string|null
			type: 'document' | 'group' | 'link'
			client_code: string
		}

		interface NewDocument extends NewNode {
			type: 'document'
			fields: NewDocumentField[]
		}

		interface NewDocumentField {
			label: string
			short_description: string | null
			value: string
			is_multiline: boolean
			is_secure: boolean
			sort: number
		}

		interface NewGroup extends NewNode {
			type: 'group'
		}

		interface CreatedNode extends Node {
			client_code: string
		}

		interface NodeForUpdate {
			id: number
			name: string
			notes: string | null
			is_effective: boolean
		}
	}
}

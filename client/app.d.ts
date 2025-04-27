declare namespace Node {
	namespace Server {
		interface CommonNode {
			id: number
			type: 'document' | 'group' | 'link'
			alias: string
			name: string
			notes: string | null
			is_effective: boolean
			created_at: number
			deleted_at: number | null
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

		interface Document extends CommonNode {
			type: 'document'
		}
	}

	namespace Client {
		interface CommonNode {
			id: number
			type: 'document' | 'group' | 'link'
			alias: string
			name: string
			notes: string|null
			isEffective: boolean
			clientCode?: string
			createdAt: Date
			deletedAt: Date|null
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

		interface NewNode {
			name: string
			notes: string|null
			type: 'document' | 'group' | 'link'
			clientCode: string
			fields?: NewDocumentField[]
		}

		interface NewGroup {
			type: 'group'
			editMode: boolean
			fields: undefined
		}
	}
}

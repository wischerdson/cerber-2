export interface ServerNode {
	id: number
	type: 'document' | 'group' | 'link'
	alias: string
	name: string
	notes: string | null
	is_effective: boolean
	created_at: number
	deleted_at: number | null
}

export interface Node {
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

export interface DocumentField {
	id: number
	label: string
	shortDescription: string | null
	value: string
	isMultiline: boolean
	isSecure: boolean
	sort: number
}

export interface ServerDocumentField {
	id: number
	label: string
	short_description: string | null
	value: string
	is_multiline: boolean
	is_secure: boolean
	sort: number
}

export interface NewNode {
	name: string
	notes: string|null
	type: 'document' | 'group' | 'link'
	clientCode: string
	fields?: NewDocumentField[]
}

export interface ServerNewNode {
	name: string
	notes: string|null
	type: 'document' | 'group' | 'link'
	fields?: ServerNewDocumentField[]
}

export interface NewDocumentField {
	label: string
	shortDescription: string | null
	value: string
	isMultiline: boolean
	isSecure: boolean
	sort: number
	clientCode: string
}

export interface ServerNewDocumentField {
	label: string
	short_description: string | null
	value: string
	is_multiline: boolean
	is_secure: boolean
	sort: number
}

export interface NewDocument extends NewNode {
	type: 'document'
	fields: NewDocumentField[]
}

export interface NewGroup extends NewNode {
	type: 'group'
	editMode: boolean
}

export interface Document extends Node {
	type: 'document'
	fields: DocumentField[]
}

export interface Group extends Node {
	type: 'group'
	editMode?: boolean
}

export const transformNodeToClient = (node: ServerNode): Node => {
	return {
		id:          node.id,
		type:        node.type,
		alias:       node.alias,
		name:        node.name,
		notes:       node.notes,
		isEffective: node.is_effective,
		createdAt:   new Date(node.created_at * 1000),
		deletedAt:   node.deleted_at === null ? null : new Date(node.deleted_at * 1000)
	}
}

export const transformNewDocumentFieldToServer = (field: NewDocumentField): ServerNewDocumentField => {
	return {
		label:             field.label,
		short_description: field.shortDescription,
		value:             field.value,
		is_multiline:      field.isMultiline,
		is_secure:         field.isSecure,
		sort:              field.sort
	}
}

export const transformNewNodeToServer = (newNode: NewNode | NewDocument): ServerNewNode => {
	return {
		name:   newNode.name,
		notes:  newNode.notes,
		type:   newNode.type,
		fields: newNode.fields?.map(transformNewDocumentFieldToServer)
	}
}

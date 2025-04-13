export interface ServerDocument {
	id: number
	is_group: boolean
	alias: string
	name: string
	notes: string|null
	is_effective: boolean
	created_at: number
	deleted_at: number|null
}

export interface Document {
	id: number
	isGroup: boolean
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
	shortDescription: string
	value: string
	isMultiline: boolean
	isSecure: boolean
	sort: number
}

export interface ServerDocumentField {
	id: number
	label: string
	short_description: string
	value: string
	is_multiline: boolean
	is_secure: boolean
	sort: number
}

export interface NewDocument {
	name: string
	notes: string|null
	isGroup: boolean
	fields: NewDocumentField[]
	clientCode: string
}

export interface ServerNewDocument {
	name: string
	notes: string|null
	is_group: boolean
	fields: ServerNewDocumentField[]
}

export interface NewDocumentField {
	label: string
	shortDescription: string
	value: string
	isMultiline: boolean
	isSecure: boolean
	sort: number
}

export interface ServerNewDocumentField {
	label: string
	short_description: string
	value: string
	is_multiline: boolean
	is_secure: boolean
	sort: number
}

export const transformDocumentToClient = (document: ServerDocument): Document => {
	return {
		id:          document.id,
		isGroup:     document.is_group,
		alias:       document.alias,
		name:        document.name,
		notes:       document.notes,
		isEffective: document.is_effective,
		createdAt:   new Date(document.created_at * 1000),
		deletedAt:   document.deleted_at === null ? null : new Date(document.deleted_at * 1000)
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

export const transformNewDocumentToServer = (newDocument: NewDocument): ServerNewDocument => {
	return {
		name:     newDocument.name,
		notes:    newDocument.notes,
		is_group: newDocument.isGroup,
		fields:   newDocument.fields.map(transformNewDocumentFieldToServer)
	}
}

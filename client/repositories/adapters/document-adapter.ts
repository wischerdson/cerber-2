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
	createdAt: Date
	deletedAt: Date|null
}

export const transformDocumentToClient = (document: ServerDocument): Document => {
	return {
		id: document.id,
		isGroup: document.is_group,
		alias: document.alias,
		name: document.name,
		notes: document.notes,
		isEffective: document.is_effective,
		createdAt: new Date(document.created_at * 1000),
		deletedAt: document.deleted_at === null ? null : new Date(document.deleted_at * 1000)
	}
}

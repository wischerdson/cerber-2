import { uid } from '~/utils/helpers'

export interface ServerSecretGroup {
	id: number
	user_id: number
	name: string
	alias: string
	description: string | null
	parent_id: number | null
	created_at: number
	deleted_at: number | null
}

export interface SecretGroup {
	id: number
	userId: number
	name: string
	alias: string
	clientCode: string
	description: string | null
	parentId: number | null
	createdAt: Date
	deletedAt: Date | null
}

export interface SecretGroupForCreate {
	name: string
	clientCode: string
	description: string | null
	parentId: number | null
}

export interface ServerSecretGroupForCreate {
	name: string
	description: string | null
	parent_id: number | null
}

export const clientToServerSecretGroupForCreate = (group: SecretGroupForCreate): ServerSecretGroupForCreate => {
	return {
		name: group.name,
		description: group.description,
		parent_id: group.parentId
	}
}

export const serverToClientSecretGroup = (group: ServerSecretGroup): SecretGroup => {
	return {
		id: group.id,
		clientCode: uid(),
		userId: group.user_id,
		name: group.name,
		alias: group.alias,
		description: group.description,
		parentId: group.parent_id,
		createdAt: new Date(group.created_at * 1000),
		deletedAt: group.deleted_at === null ? null : new Date(group.deleted_at * 1000)
	}
}

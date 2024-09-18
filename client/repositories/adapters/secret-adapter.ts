import { uid } from '~/utils/helpers'

export interface ServerSecret {
	id: number
	name: string
	notes: string
	is_uptodate: boolean
	fields: ServerSecretField[]
	created_at: number
	updated_at: number
}

export interface ServerSecretField {
	id: number
	label: string
	short_description: string | null
	value: string
	secure: boolean
	multiline: boolean
	sort: number
}

export interface Secret {
	id: number
	name: string
	notes: string
	isUptodate: boolean
	fields: SecretField[]
	clientCode: string
	createdAt: Date
	updatedAt: Date
}

export interface SecretField {
	id: number,
	label: string
	shortDescription: string | null
	value: string
	secure: boolean
	multiline: boolean
	sort: number
	clientCode: string
}

export interface SecretForCreate extends Omit<Secret, 'id' | 'fields' | 'createdAt' | 'updatedAt'> {
	fields: SecretFieldForCreate[]
}

export interface SecretFieldForCreate extends Omit<SecretField, 'id'> {

}

export interface ServerSecretForCreate extends Omit<ServerSecret, 'id' | 'fields' | 'created_at' | 'updated_at'> {
	fields: ServerSecretFieldForCreate[]
}

export interface ServerSecretFieldForCreate extends Omit<ServerSecretField, 'id'> {

}

export const clientToServerSecretFieldForCreate = (field: SecretFieldForCreate): ServerSecretFieldForCreate => {
	return {
		label: field.label,
		short_description: field.shortDescription,
		value: field.value,
		secure: field.secure,
		multiline: field.multiline,
		sort: field.sort
	}
}

export const clientToServerSecretForCreate = (secret: SecretForCreate): ServerSecretForCreate => {
	return {
		name: secret.name,
		notes: secret.notes,
		is_uptodate: secret.isUptodate,
		fields: secret.fields.map(clientToServerSecretFieldForCreate)
	}
}

export const serverToClientSecretField = (field: ServerSecretField): SecretField => {
	return {
		id: field.id,
		label: field.label,
		shortDescription: field.short_description,
		value: field.value,
		secure: field.secure,
		multiline: field.multiline,
		sort: field.sort,
		clientCode: uid()
	}
}

export const serverToClientSecret = (secret: ServerSecret): Secret => {
	return {
		id: secret.id,
		name: secret.name,
		notes: secret.notes,
		isUptodate: secret.is_uptodate,
		fields: secret.fields.map(serverToClientSecretField),
		clientCode: uid(),
		createdAt: new Date(secret.created_at * 1000),
		updatedAt: new Date(secret.updated_at * 1000),
	}
}
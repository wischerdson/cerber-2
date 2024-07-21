import { usePostReq } from '~/composables/use-request'

export type ServerSecretField = {
	label: string
	short_description: string | null
	value: string
	secure: boolean
	multiline: boolean
	sort: number
}

export type ServerSecret = {
	name: string
	notes: string
	fields: ServerSecretField[]
}

export type ClientSecretField = {
	label: string
	shortDescription: string | null
	value: string
	secure: boolean
	multiline: boolean
	sort: number
}

export type ClientSecret = {
	name: string
	notes: string
	fields: ClientSecretField[]
}

export const create = (secret: ClientSecret) => {
	return usePostReq(
		'/secrets', clientToServerSecret(secret)
	).sign('default').shouldEncrypt().send()
}

const clientToServerSecretField = (field: ClientSecretField): ServerSecretField => {
	return {
		label: field.label,
		short_description: field.shortDescription,
		value: field.value,
		secure: field.secure,
		multiline: field.multiline,
		sort: field.sort
	}
}

const clientToServerSecret = (secret: ClientSecret): ServerSecret => {
	return {
		name: secret.name,
		notes: secret.notes,
		fields: secret.fields.map(clientToServerSecretField)
	}
}

const serverToClientSecretField = (field: ServerSecretField): ClientSecretField => {
	return {
		label: field.label,
		shortDescription: field.short_description,
		value: field.value,
		secure: field.secure,
		multiline: field.multiline,
		sort: field.sort
	}
}

const serverToClientSecret = (secret: ServerSecret): ClientSecret => {
	return {
		name: secret.name,
		notes: secret.notes,
		fields: secret.fields.map(serverToClientSecretField)
	}
}

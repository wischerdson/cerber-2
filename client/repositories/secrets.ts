import { useGetReq, usePostReq } from '~/composables/use-request'

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
	is_uptodate: boolean
	fields: ServerSecretField[]
	created_at: number
	updated_at: number
}

export type ServerSecretForWrite = Omit<ServerSecret, 'created_at' | 'updated_at'>

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
	isUptodate: boolean
	fields: ClientSecretField[]
	createdAt: Date
	updatedAt: Date
}

export const createSecret = (secret: ClientSecret) => {
	return usePostReq(
		'/secrets', clientToServerSecret(secret)
	).sign().shouldEncrypt().send()
}

export const fetchSecrets = async () => {
	const serverSecrets = await useGetReq<ServerSecret[]>('/secrets').sign().send();
	const clientSecrets = serverSecrets.map(s => serverToClientSecret(s))

	return clientSecrets
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

const clientToServerSecret = (secret: ClientSecret): ServerSecretForWrite => {
	return {
		name: secret.name,
		notes: secret.notes,
		is_uptodate: secret.isUptodate,
		fields: secret.fields.map(clientToServerSecretField),
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
		isUptodate: secret.is_uptodate,
		fields: secret.fields.map(serverToClientSecretField),
		createdAt: new Date(secret.created_at * 1000),
		updatedAt: new Date(secret.updated_at * 1000),
	}
}

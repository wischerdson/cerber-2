import { usePostReq } from '~/composables/use-request'

export type NewSecretField = {
	name: string
	short_description: string
	value: string
	secure: boolean
	multiline: boolean
}

export type NewSecret = {
	name: string
	notes: string
	fields: NewSecretField[]
}

export const create = (secret: NewSecret) => {
	return usePostReq('/secrets', secret).sign('default').shouldEncrypt().send()
}

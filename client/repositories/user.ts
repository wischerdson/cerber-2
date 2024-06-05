import { useGetReq } from '~/composables/use-request'

export type User = {
	id: number,
	first_name: string,
	last_name: string,
	patronymic: string|null,
	email: string|null,
	phone: string|null,
	timezone: string|null,
	timezone_offset: number|null,
	created_at: number
}

export const fetchUser = () => useGetReq<User>('/current-user')

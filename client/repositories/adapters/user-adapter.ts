export interface ServerUser {
	id: number
	first_name: string
	last_name: string
	email: string|null
	timezone: string|null
	timezone_offset: number|null
	is_admin: boolean
	created_at: number
	deleted_at: number
}

export interface User {
	id: number
	firstName: string
	lastName: string
	email: string|null
	timezone: string|null
	timezoneOffset: number|null
	isAdmin: boolean
	created_at: Date
	deleted_at: Date|null
}

export const serverToClientUser = (user: ServerUser): User => {
	return {
		id: user.id,
		firstName: user.first_name,
		lastName: user.last_name,
		email: user.email,
		timezone: user.timezone,
		timezoneOffset: user.timezone_offset,
		isAdmin: user.is_admin,
		created_at: new Date(user.created_at * 1000),
		deleted_at: user.deleted_at === null ? null : new Date(user.deleted_at * 1000)
	}
}

declare namespace Dto.Auth {
	interface User {
		id: number
		firstName: string
		lastName: string
		email: string|null
		timezone: string|null
		timezoneOffset: number|null
		isAdmin: boolean
		createdAt: Date
		deletedAt: Date|null
	}

	namespace Server {
		interface User {
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
	}
}

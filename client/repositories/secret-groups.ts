import { useGetReq } from '~/composables/use-request'

export const getGroups = async () => {
	const groups = await useGetReq('/groups').sign().shouldEncrypt().send()


}

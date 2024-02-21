<template>
	<div class="pt-11 pb-16 relative">
		<div class="flex flex-col items-center text-dark-gray-1">
			<CerberLogo class="w-24" />
			<CerberTextLogo class="w-44 mt-4" />
		</div>

		<div class="absolute min-h-screen inset-0 overflow-hidden pointer-events-none -z-10 opacity-[.13]">
			<img class="absolute right-1/2 top-0 w-[4290px] max-w-none -translate-y-[1590px] translate-x-[2386px]" src="/images/blurs.png">
		</div>

		<div class="mt-28">

			<div class="max-w-sm bg-dark-gray-2/50 backdrop-blur-xl rounded-2xl mx-auto py-7">
				<div class="text-center">
					<h1 class="text-2xl font-medium">Войти</h1>
				</div>
				<form class="px-8 mt-10 space-y-8" @submit.prevent="sendForm" autocomplete="off">
					<TextField :validation-field="useField('login')">
						<template #label>Логин</template>
					</TextField>
					<TextField type="password" :validation-field="useField('password')">
						<template #label>Пароль</template>
					</TextField>

					<div>
						<TheAlert
							class="mb-8"
							:show="serverError === 'auth_credentials_error'"
							appearance="error"
						>Неверный логин или пароль</TheAlert>

						<div class="flex justify-center">
							<TheButton
								class="w-[54px] h-[54px]"
								color="primary"
								rounded square custom-sizing
								:loading="pending"
								type="submit"
							>
								<icon name="material-symbols:arrow-forward-rounded" size="32px" />
							</TheButton>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import CerberTextLogo from '~/assets/svg/cerber-text-logo.svg'
import CerberLogo from '~/assets/svg/cerber-logo.svg'
import TextField from '~/components/ui/TextField.vue'
import TheButton from '~/components/ui/Button.vue'
import TheAlert from '~/components/ui/Alert.vue'
import { useValidation } from '~/composables/use-validation'
import { usePostReq } from '~/composables/use-request'
import { definePageMeta, ref, useHead } from '#imports'
import { object, string } from 'yup'

useHead({ title: 'Cerber - Авторизация' })
definePageMeta({ layout: 'auth' })

const pending = ref(false)
const serverError = ref<string|null>()

const { useField, validate, touchAll } = useValidation().defineRules(object({
	login: string().required('Введите логин'),
	password: string().required('Введите пароль')
}))

type AuthTokenResponse = {
	token_type: string,
	access_token: string,
	refresh_token: string
}

const sendForm = async () => {
	const form = await validate()
	touchAll()

	if (!form) {
		return
	}

	pending.value = true

	const promise = usePostReq<AuthTokenResponse>('/auth/token', {
		grant_type: 'password',
		...form
	}, { 'responseType': 'arrayBuffer' }).send()

	return promise
		.then(resp => {

		})
		.catch((error) => {
			if (error.data) {
				return serverError.value = error.data.error_reason
			}
		})
		.finally(() => pending.value = false)
}

</script>

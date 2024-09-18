<template>
	<div class="pt-11 pb-16 relative">
		<div class="flex flex-col items-center dark:text-dark-gray-1 text-gray-600">
			<CerberLogo class="w-24" />
			<CerberTextLogo class="w-44 mt-4" />
		</div>

		<div class="absolute min-h-screen inset-0 overflow-hidden pointer-events-none -z-10 opacity-[.1]">
			<img class="absolute right-1/2 top-0 w-[4290px] max-w-none -translate-y-[1590px] translate-x-[2386px]" src="/images/blurs.png">
		</div>

		<div class="mt-28">
			<div class="tile max-w-sm dark:bg-dark-gray-2/50 bg-white/50 backdrop-blur-xl rounded-[20px] mx-auto py-7">
				<div class="text-center">
					<h1 class="text-2xl font-medium">Войти</h1>
				</div>
				<form class="px-8 mt-10 space-y-6" @submit.prevent="sendForm" autocomplete="off">
					<TextField :validation-field="useField('login')" label="Логин" />
					<TextField type="password" :validation-field="useField('password')" label="Пароль" />

					<div>
						<TheAlert
							class="mb-8 text-sm"
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
								<icon name="material-symbols:arrow-forward-rounded" size="28px" />
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
import TextField from '~/components/ui/Input.vue'
import TheButton from '~/components/ui/Button.vue'
import TheAlert from '~/components/ui/Alert.vue'
import { useValidation } from '~/composables/use-validation'
import { definePageMeta, ref, useAuth, useHead, useRouter } from '#imports'
import { object, string } from 'yup'

useHead({ title: 'Cerber - Авторизация' })
definePageMeta({ layout: 'auth' })

const pending = ref(false)
const serverError = ref<string|null>()

const router = useRouter()

const { useField, validate, touchAll } = useValidation().defineRules(object({
	login: string().required('Введите логин').default('admin'),
	password: string().required('Введите пароль').default('123123')
}))

const sendForm = async () => {
	const form = await validate()
	touchAll()

	if (!form) {
		return
	}

	pending.value = true

	try {
		await useAuth('default').signIn(form.login, form.password)
		serverError.value = null

		// window.location.reload()

		await router.replace({ force: true })
	} catch (error: any) {
		if (error.data && 'error_reason' in error.data) {
			serverError.value = error.data.error_reason
		}
	}

	pending.value = false
}

</script>

<style lang="scss" scoped>

.tile {
	box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.07);
}

</style>

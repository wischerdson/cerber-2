<template>
	<div class="pt-11 pb-16">
		<div class="flex flex-col items-center text-dark-gray-1">
			<CerberLogo class="w-24" />
			<CerberTextLogo class="w-44 mt-4" />
		</div>

		<div class="fixed inset-0 -z-10">
			<div class="absolute w-[889px] h-[514px] top-0 right-0" style="background-image: url('/images/ellipse-6.png')"></div>
			<div class="absolute w-[559px] h-[832px] left-0 bottom-0" style="background-image: url('/images/ellipse-7.png')"></div>
		</div>

		<div class="mt-28">
			<div class="max-w-sm bg-dark-gray-2 rounded-2xl mx-auto py-7">
				<div class="text-center">
					<h1 class="text-2xl font-medium">Войти</h1>
				</div>
				<form class="px-8 mt-10 space-y-8" @submit.prevent="sendForm">
					<TextField v-model="login.model.value" @change="login.touch()">
						<template #label>Логин</template>
					</TextField>
					<div v-if="login.hasErrors()">
						<span class="text-red-300">{{ login.error }}</span>
					</div>
					<TextField type="password" v-model="password.model.value" @change="password.touch()">
						<template #label>Пароль</template>
					</TextField>
					<div v-if="password.hasErrors()">
						<span class="text-red-300">{{ password.error }}</span>
					</div>

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
					{{ getObject() }}
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
import { useValidation } from '~/composables/useValidation'
import { ref } from '#imports'
import { object, string } from 'yup'

const pending = ref(false)

const { useField, getObject, validate, touchAll } = useValidation().defineRules(object({
	login: string().required('Требуется ввести логин').min(4, 'Логин должен состоять минимум из 4 символов'),
	password: string().required('Требуется ввести пароль')
}))

const login = useField('login')
const password = useField('password')

const sendForm = async () => {
	const form = await validate()
	touchAll()

	if (!form) {
		return
	}

	pending.value = true

	console.log('Валидация прошла', form)
}

</script>

<template>
	<div>
		<div class="flex items-center space-x-3">
			<div class="bg-black/5 w-9 h-9 flex items-center justify-center rounded-lg">
				<span class="text-2xl font-medium">?</span>
			</div>
			<h2 class="font-bold text-gray-850 text-1.5xl tracking-wide">Новый доступ</h2>
		</div>
		<TheForm
			class="mt-6"
			name="FTP"
			notes="Работает только из-под корпоративного VPN"
			:is-uptodate="true"
			clientCode="hehe0"
			:fields="[
				{
					label: 'Хост',
					value: 'somesite.ru',
					shortDescription: '',
					secure: false,
					multiline: false,
					clientCode: 'hehe1'
				},
				{
					label: 'Логин',
					value: 'root',
					shortDescription: '',
					secure: false,
					multiline: true,
					clientCode: 'hehe2'
				},
				{
					label: 'Пароль',
					value: '123123',
					shortDescription: 'Какое-то короткое описание для поля',
					secure: true,
					multiline: false,
					clientCode: 'hehe3'
				}
			]"
			@save="save"
		/>
	</div>
</template>

<script setup lang="ts">

import type { Props } from './Form.vue'
import TheForm from '~/components/account/secrets/Form.vue'
import { createSecret } from '~/repositories/secrets'

const save = (model: Props) => {
	createSecret(model)
}

</script>

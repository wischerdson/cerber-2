<template>
	<div>
		<h2 class="font-bold text-gray-850 text-1.5xl tracking-wide">Новый доступ</h2>
		<TheForm
			class="mt-6"
			name="FTP"
			notes="Работает только из-под корпоративного VPN"
			:fields="[
				{
					label: 'Хост',
					value: 'h04.twinscom.ru',
					shortDescription: '',
					secure: false,
					multiline: false,
					sort: 5
				},
				{
					label: 'Логин',
					value: 'root',
					shortDescription: '',
					secure: false,
					multiline: false,
					sort: 5
				},
				{
					label: 'Пароль',
					value: '123123',
					shortDescription: 'Какое-то короткое описание для поля',
					secure: true,
					multiline: false,
					sort: 5
				}
			]"
			@save="save"
		/>
	</div>
</template>

<script setup lang="ts">

import type { Props } from './Form.vue'
import TheForm from '~/components/account/secrets/Form.vue'
import { create } from '~/repositories/secrets'

const save = (model: Props) => {
	create(model)
}

/*
[
	{ label: 'Веб-сайт', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
	{ label: 'Веб-сайт 1', secure: false, multiline: false, value: '', shortDescription: null, sort: 2 },
	{ label: 'Веб-сайт 2', secure: false, multiline: false, value: '', shortDescription: null, sort: 3 },
	{ label: 'Веб-сайт 3', secure: false, multiline: false, value: '', shortDescription: null, sort: 4 },
	{ label: 'Веб-сайт 4', secure: false, multiline: false, value: '', shortDescription: null, sort: 5 },
	{ label: 'Веб-сайт 5', secure: false, multiline: false, value: '', shortDescription: null, sort: 6 },
	{ label: 'Веб-сайт 6', secure: false, multiline: false, value: '', shortDescription: null, sort: 7 },
	// { name: 'Логин', secure: false, multiline: false, value: '' },
	// { name: 'Пароль', secure: true, multiline: false, value: '' }
]
*/

</script>

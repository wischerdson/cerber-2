<template>
	<div>
		<h2 class="font-bold text-gray-850 text-1.5xl tracking-wide">Новый доступ</h2>
		<TheForm
			class="mt-6"
			name=""
			:fields="[
				{ label: 'Веб-сайт', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				{ label: 'Веб-сайт 1', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				{ label: 'Веб-сайт 2', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				{ label: 'Веб-сайт 3', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				{ label: 'Веб-сайт 4', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				{ label: 'Веб-сайт 5', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				{ label: 'Веб-сайт 6', secure: false, multiline: false, value: '', shortDescription: null, sort: 1 },
				// { name: 'Логин', secure: false, multiline: false, value: '' },
				// { name: 'Пароль', secure: true, multiline: false, value: '' }
			]"
			notes=""
			@save="save"
		/>
	</div>
</template>

<script setup lang="ts">

import type { Props } from './Form.vue'
import TheForm from '~/components/account/secrets/Form.vue'
import { create } from '~/repositories/secrets'

const save = (model: Props) => {
	// create(model)
}

</script>

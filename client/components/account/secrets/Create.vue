<template>
	<div>
		<h2 class="font-bold text-gray-850 text-1.5xl tracking-wide">Новый доступ</h2>
		<TheForm
			class="mt-6"
			name=""
			:fields="[
				{ name: 'Веб-сайт', secure: false, multiline: false, value: '' },
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
	create(model)
}

</script>

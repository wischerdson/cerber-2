<template>
	<div>
		<h2 class="font-bold text-gray-850 text-1.5xl tracking-wide">Новый доступ</h2>
		<TheForm class="mt-6" name="" :fields="[
			{ name: 'Веб-сайт', secure: false }, { name: 'Логин', secure: false }, { name: 'Пароль', secure: true }
		]" notes="" />
	</div>
</template>

<script setup lang="ts">

import TheForm from '~/components/account/secrets/Form.vue'

</script>

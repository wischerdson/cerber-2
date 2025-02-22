<template>
	<div class="flex justify-center gap-6">
		<div class="grow max-w-2xl">
			<TheSearch />

			<UiContentTile class="mt-6 pb-6">
				<div class="px-2 pt-4">
					<TheBreadcrumb />
				</div>
				<div class="px-6 mt-4">
					<h1 class="font-medium text-xl">Реклама и маркетинг</h1>

					<hr class="w-full border-gray-100 dark:border-gray-850 mt-6 mb-6">

					<SecretGroupList />
					<SecretList />
				</div>
			</UiContentTile>
		</div>
		<UiContentTile class="py-6 h-[400px] grow max-w-md px-6">
			<h2 class="font-semibold text-xl">Какой-то доступ</h2>
		</UiContentTile>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import UiContentTile from '~/components/ui/ContentTile.vue'
import TheBreadcrumb from '~/components/account/Breadcrumb.vue'
import SecretGroupList from '~/components/account/secrets/list/SecretGroupList.vue'
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import TheSearch from '~/components/account/Search.vue'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

<style>

.secret-item {
	&:hover {
		.secret-icon--letter {
			background-color: var(--color-gray-100);
		}
	}
}

html.dark {
	.secret-item {
		&:hover {
			.secret-icon--letter {
				background-color: var(--color-gray-800);
			}
		}
	}
}

</style>

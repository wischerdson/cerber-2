<template>
	<div class="flex justify-center items-start gap-6">
		<div class="grow max-w-2xl">
			<TheSearch />

			<UiContentTile class="mt-6 pb-6">
				<div class="px-2.5 pt-4">
					<TheBreadcrumb />
				</div>
				<div class="px-6 mt-4">
					<h1 class="font-medium text-xl">Реклама и маркетинг</h1>


						<pre>{{ route.params }}</pre>


					<hr class="w-full border-gray-100 dark:border-gray-850 mt-6 mb-6">

					<SecretGroupList />
					<SecretList />
				</div>
			</UiContentTile>
		</div>
		<UiContentTile class="py-6 grow max-w-md px-6">
			<SecretFormCreate />
		</UiContentTile>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import UiContentTile from '~/components/ui/ContentTile.vue'
import TheBreadcrumb from '~/components/account/Breadcrumb.vue'
import SecretGroupList from '~/components/account/secrets/list/groups/SecretGroupList.vue'
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import TheSearch from '~/components/account/Search.vue'
import SecretFormCreate from '~/components/account/secrets/form/FormCreate.vue'
import { useSecretGroupsStore } from '~/store/secret-groups'
import { useAccountLayoutLoaderStore } from '~/store/loaders'
import { useRoute, useRouter } from 'vue-router'

definePageMeta({ middleware: 'auth' })

const route = useRoute()

console.log(useRouter().getRoutes())

useHead({ title: 'Cerber - Доступы' })

const loaderStore = useAccountLayoutLoaderStore()
const groupsStore = useSecretGroupsStore()

loaderStore.addPromise(groupsStore.fetch(null))

</script>

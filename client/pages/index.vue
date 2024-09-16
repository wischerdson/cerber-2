<template>
	<DefaultPageView>
		<div class="flex items-start gap-6">
			<Sidebar />
			<ContentTile class="w-full">
				<div>
					<div class="text-xs mb-2 text-gray-400 dark:text-gray-600">
						Пространство
					</div>
					<div class="flex items-center -ml-1.5">
						<icon class="mr-1.5" name="material-symbols:attach-money-rounded" size="20px" />
						<h2 class="font-medium text-lg">Коммерческие проекты</h2>
					</div>
				</div>

				<div class="mt-8">
					<div>
						<SecretList :secrets="secretsStore.secrets" />
					</div>

					<hr class="my-5 border-gray-100 dark:border-gray-900">

					<div>
						<GroupList />
					</div>
				</div>
			</ContentTile>

			<ContentTile class="w-full" v-if="secretsStore.mode === 'create'">
				<SecretCreate />
			</ContentTile>
			<ContentTile class="w-full" v-if="secretsStore.mode === 'view'">
				<SecretView :secret="secretsStore.secretForView" v-if="secretsStore.secretForView" />
			</ContentTile>
		</div>
	</DefaultPageView>
</template>

<script setup lang="ts">

import DefaultPageView from '~/components/ui/DefaultPageView.vue'
import ContentTile from '~/components/ui/ContentTile.vue'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'
import SecretView from '~/components/account/secrets/Detail.vue'
import SecretCreate from '~/components/account/secrets/Create.vue'
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import GroupList from '~/components/account/secrets/list/GroupList.vue'
import { definePageMeta, useHead } from '#imports'
import { useSecretsStore } from '~/store/secrets'
import { onMounted } from 'vue'
import { useAccountLayoutLoaderStore } from '~/store/loaders'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

const secretsStore = useSecretsStore()

const loaderStore = useAccountLayoutLoaderStore()

loaderStore.addPromise(new Promise<void>(resolve => {
	onMounted(async () => {
		await secretsStore.fetch()
		resolve()
	})
}))

</script>

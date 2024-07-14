<template>
	<DefaultPageView>
		<div class="flex items-start gap-6">
			<Sidebar />
			<ContentTile class="w-full">
				<div>
					<div class="text-xs mb-2 text-gray-400">
						Пространство
					</div>
					<div class="flex items-center -ml-1.5">
						<icon class="mr-1.5" name="material-symbols:attach-money-rounded" size="20px" />
						<h2 class="font-medium text-lg">Коммерческие проекты</h2>
					</div>
				</div>

				<div class="mt-8">
					<div>
						<SecretList />
					</div>

					<hr class="my-5 border-gray-100">

					<div>
						<GroupList />
					</div>
				</div>
			</ContentTile>

			<ContentTile class="w-full" v-if="secretsStore.mode.value === 'create'">
				<SecretCreate />
			</ContentTile>
			<ContentTile class="w-full" v-if="secretsStore.mode.value === 'view'">
				<SecretView />
			</ContentTile>
		</div>
	</DefaultPageView>
</template>

<script setup lang="ts">

import DefaultPageView from '~/components/ui/DefaultPageView.vue'
import ContentTile from '~/components/ui/ContentTile.vue'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'
import SecretView from '~/components/account/secrets/View.vue'
import SecretEdit from '~/components/account/secrets/Edit.vue'
import SecretCreate from '~/components/account/secrets/Create.vue'
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import GroupList from '~/components/account/secrets/list/GroupList.vue'
import { definePageMeta, useHead } from '#imports'
import { useSecretsStore } from '~/store/secrets'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

const secretsStore = useSecretsStore()

</script>

<style scoped lang="scss">

.a {}

</style>

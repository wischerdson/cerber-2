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
			<div class="space-y-6">
				<ContentTile class="w-full">
					<SecretView />
				</ContentTile>
				<ContentTile class="w-full">
					<SecretEdit />
				</ContentTile>
			</div>
		</div>
	</DefaultPageView>
</template>

<script setup lang="ts">

import DefaultPageView from '~/components/ui/DefaultPageView.vue'
import ContentTile from '~/components/ui/ContentTile.vue'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'
import SecretView from '~/components/account/secrets/View.vue'
import SecretEdit from '~/components/account/secrets/Edit.vue'
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import GroupList from '~/components/account/secrets/list/GroupList.vue'
import { definePageMeta, useHead } from '#imports'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

<style scoped lang="scss">

.a {}

</style>

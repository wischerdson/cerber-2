<template>
	<DefaultPageView>
		<div class="flex items-start gap-6 mt-4">
			<Sidebar />
			<ContentTile class="w-full">
				<h2 class="">
					<span class="text-gray-600">Результаты поиска по запросу</span> <span class="font-medium">"primesn"</span>:
				</h2>
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
			<ContentTile class="w-full">
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
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import GroupList from '~/components/account/secrets/list/GroupList.vue'
import { definePageMeta, useHead } from '#imports'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

<style scoped lang="scss">

.a {}

</style>

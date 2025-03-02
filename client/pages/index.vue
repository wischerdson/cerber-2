<template>
	<div class="flex justify-center items-start gap-6">
		<div class="grow max-w-2xl">
			<TheSearch />

			<UiContentTile class="mt-6 pb-6">
				<div class="px-2.5 pt-4">
					<TheBreadcrumb />
				</div>
				<div class="px-6 mt-4">
					<SecretGroupList />
				</div>
			</UiContentTile>
		</div>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import UiContentTile from '~/components/ui/ContentTile.vue'
import TheBreadcrumb from '~/components/account/Breadcrumb.vue'
import SecretGroupList from '~/components/account/secrets/list/groups/SecretGroupList.vue'
import TheSearch from '~/components/account/Search.vue'
import { useSecretGroupsStore } from '~/store/secret-groups'
import { useAccountLayoutLoaderStore } from '~/store/loaders'
import { useBreadcrumbStore } from '~/store/breadcrumb'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

const loaderStore = useAccountLayoutLoaderStore()
const groupsStore = useSecretGroupsStore()
const breadcrumbStore = useBreadcrumbStore()

breadcrumbStore.clearChain()

loaderStore.addPromise(groupsStore.fetch(null))

</script>

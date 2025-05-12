<template></template>

<script setup lang="ts">

import { createError, definePageMeta, useHead } from '#imports'
import { useAccountLayoutLoaderStore } from '~/store/loaders'
import { useRoute } from 'vue-router'
import { useNodeStore } from '~/store/nodes'

definePageMeta({ middleware: 'auth', layout: 'account-node-list' })

const route = useRoute()

useHead({ title: 'Cerber - Доступы' })

const loaderStore = useAccountLayoutLoaderStore()
const nodeStore = useNodeStore()

if (Array.isArray(route.params.chain)) {
	const chain = route.params.chain.filter(n => n)

	loaderStore.addPromise(
		nodeStore.fetchNodesByChain(chain).catch(e => {
			if ('error_reason' in e.data && e.data.error_reason == 'incorrect_node_chain') {
				throw createError({
					statusCode: 404,
					statusMessage: 'Page Not Found'
				})
			}
		})
	)
}

</script>

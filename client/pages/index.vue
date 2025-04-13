<template></template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import { useAccountLayoutLoaderStore } from '~/store/loaders'
import { useBreadcrumbStore } from '~/store/breadcrumb'
import { useDocumentGroupAggregateStore } from '~/store/aggregates/document-group-aggregate-store'

definePageMeta({ middleware: 'auth', layout: 'account-secrets' })

useHead({ title: 'Cerber - Доступы' })

const loaderStore = useAccountLayoutLoaderStore()
const breadcrumbStore = useBreadcrumbStore()

breadcrumbStore.clearChain()

loaderStore.addPromise(
	useDocumentGroupAggregateStore().fetchByParentId(null)
)

</script>

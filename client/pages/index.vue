<template></template>

<script setup lang="ts">

import { definePageMeta, useHead } from '#imports'
import { useSecretGroupsStore } from '~/store/secret-groups'
import { useAccountLayoutLoaderStore } from '~/store/loaders'
import { useBreadcrumbStore } from '~/store/breadcrumb'
import { useSecretGroupAggregateStore } from '~/store/aggregates/secret-group-aggregate-store'

definePageMeta({ middleware: 'auth', layout: 'account-secrets' })

useHead({ title: 'Cerber - Доступы' })

const loaderStore = useAccountLayoutLoaderStore()
const groupsStore = useSecretGroupsStore()
const breadcrumbStore = useBreadcrumbStore()

breadcrumbStore.clearChain()

loaderStore.addPromise(
	useSecretGroupAggregateStore().fetch({ alias: null })
)

</script>

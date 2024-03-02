<template>
	<div class="container py-7">
		<div>
			<h1 class="font-bold text-gray-700 dark:text-gray-100 text-2.5xl">Доступы</h1>
		</div>
		<div class="flex items-start gap-5 mt-5">
			<Sidebar />

			<div class="flex-1 h-[600px] dark:bg-gray-900/50 bg-gray-50/50 backdrop-blur-xl rounded-2xl"></div>
		</div>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta } from '#imports'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'

definePageMeta({ middleware: 'auth', layout: 'account' })

</script>

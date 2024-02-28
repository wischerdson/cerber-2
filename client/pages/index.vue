<template>
	<div class="container py-7">
		<div>
			<h1 class="font-bold text-neutral-700 text-2.5xl">Доступы</h1>
		</div>
		<div class="flex items-start gap-5 mt-5">
			<Sidebar />
			<div class="flex-1 h-[600px] dark:bg-[#111]/60 bg-[#ebebeb]/50 backdrop-blur-xl rounded-2xl">

			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import { definePageMeta } from '#imports'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'


definePageMeta({ middleware: 'auth', layout: 'account' })

</script>

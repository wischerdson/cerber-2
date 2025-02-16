<template>
	<NuxtLayout name="default">
		<div class="flex items-start gap-6">
			<div class="h-screen pl-6 py-6">
				<div class="min-w-3xs h-full flex flex-col justify-between">
					<div>
						<CerberLogo class="h-12" />
					</div>
				</div>
			</div>
			<div class="w-full">
				<div class="pr-6 py-6 flex justify-between">
					<div class="grow max-w-lg">
						<TheSearch />
					</div>
					<UserBadge class="shrink-0" />
				</div>
				<div>
					<slot></slot>
				</div>
			</div>
		</div>
	</NuxtLayout>
</template>

<script setup lang="ts">

import CerberLogo from '~/assets/svg/cerver-logo-with-text.svg'
import UserBadge from '~/components/account/UserBadge.vue'
import TheSearch from '~/components/account/Search.vue'

</script>

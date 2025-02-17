<template>
	<NuxtLayout name="default">
		<div class="flex items-start gap-6">
			<div class="h-screen pl-6 py-4">
				<div class="min-w-3xs h-full flex flex-col justify-between">
					<div>
						<CerberLogo class="h-12" />
					</div>
				</div>
			</div>
			<div class="w-full">
				<!-- <div class="flex justify-between">
					<div class="absolute top-4 inset-x-0 flex justify-center pointer-events-none">
						<div class="grow max-w-lg mx-auto pointer-events-auto">
							<TheSearch />
						</div>
					</div>
					<UserBadge class="ml-auto shrink-0" />
				</div> -->
				<div class="pr-6 py-6 flex justify-between">
					<div class="grow max-w-lg">
						<TheSearch />
					</div>
					<UserBadge class="shrink-0" />
				</div>
				<div class="mt-10">
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

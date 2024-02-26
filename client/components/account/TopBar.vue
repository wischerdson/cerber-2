<template>
	<section class="top-bar">
		<div class="container relative">
			<div class="flex py-2">
				<div class="flex-1">
					<CerberLogo class="h-12 dark:text-white/60 text-black/60" />
				</div>
				<div class="items-center">
					<div>
						<Search />
					</div>
				</div>
				<div class="flex-1 flex justify-end">
					<UserBadge />
				</div>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">

import CerberLogo from '~/assets/svg/cerber-logo.svg'
import UserBadge from '~/components/account/TopBar/UserBadge.vue'
import Search from '~/components/account/TopBar/Search.vue'

</script>

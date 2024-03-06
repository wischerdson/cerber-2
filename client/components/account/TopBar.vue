<template>
	<section class="top-bar">
		<div class="container relative">
			<div class="flex py-2">
				<NuxtLink class="flex-1" to="/">
					<TheClickable>
						<CerberLogo class="h-12 dark:text-white/60 text-black/60" />
					</TheClickable>
				</NuxtLink>
				<div class="items-center">
					<SpacesManager />
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
import SpacesManager from '~/components/account/TopBar/SpacesManager.vue'
import TheClickable from '~/components/ui/Clickable.vue'

</script>

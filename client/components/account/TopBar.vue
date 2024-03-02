<template>
	<section class="top-bar">
		<div class="container relative">
			<div class="flex py-2">
				<div class="flex-1">
					<NuxtLink to="/">
						<TheClickable>
							<CerberLogo class="h-12 dark:text-white/60 text-black/60" />
						</TheClickable>
					</NuxtLink>
				</div>
				<div class="items-center">
					<SpacesSwitch />
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
import SpacesSwitch from '~/components/account/TopBar/SpacesSwitch.vue'
import TheClickable from '~/components/ui/Clickable.vue'

</script>

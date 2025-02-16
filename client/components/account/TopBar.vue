<template>
	<section class="top-bar">
		<UiContainer class="relative z-20">
			<div class="flex py-2">
				<div class="flex-1">
					<UiClickable>
						<NuxtLink class="flex items-end" to="/">
							<CerberLogo class="h-12 dark:text-white/70 text-black/70" />
							<CerberTextLogo class="h-2.5 dark:text-white/70 text-black/70 -translate-x-4" />
						</NuxtLink>
					</UiClickable>
				</div>
				<div class="items-center">
					<SpacesManager />
				</div>
				<div class="flex-1 flex justify-end">
					<UserBadge />
				</div>
			</div>
		</UiContainer>
	</section>
</template>

<script setup lang="ts">

import CerberLogo from '~/assets/svg/cerber-logo.svg'
import CerberTextLogo from '~/assets/svg/cerber-text-logo.svg'
import UserBadge from '~/components/account/TopBar/UserBadge.vue'
import SpacesManager from '~/components/account/TopBar/SpacesManager.vue'
import UiClickable from '~/components/ui/Clickable.vue'
import UiContainer from '~/components/ui/Container.vue'

</script>

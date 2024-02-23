<template>
	<section class="top-bar">
		<div class="container relative">
			<div class="flex py-2">
				<div class="flex-1">
					<CerberLogo class="h-12 text-white/60" />
				</div>
				<div class="flex items-center">
					<div>Hello</div>
				</div>
				<div class="flex-1 flex justify-end">
					<div class="relative">
						<TheClickable
							class="user-pill rounded-full pr-2 h-12 flex items-center"
							:class="{ 'menu-is-shown': menu }"
							@click.stop="menu = !menu"
						>
							<div>
								<img class="h-12 rounded-full" src="/images/avatar.jpg" alt="Avatar">
							</div>
							<div class="ml-3">
								<div>
									<span class="text-white/85">Даниил </span>
									<span class="text-white/50">О.</span>
								</div>
							</div>
							<div class="ml-3">
								<icon size="26px" name="material-symbols:arrow-drop-down-rounded" />
							</div>
						</TheClickable>

						<transition>
							<div class="menu-wrapper absolute right-0 top-full pt-4" v-if="menu">
								<div class="menu rounded-xl w-56 relative px-2.5 py-2.5 z-50" ref="$menu">
									<div class="absolute right-0 -top-7">
										<icon class="arrow-top" size="48px" name="material-symbols:arrow-drop-up-rounded" />
									</div>
									<div>
										<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4">
											<GearIcon class="mr-3 h-4 w-4" />
											<span>Настройки</span>
										</TheClickable>
									</div>
									<div>
										<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4">
											<icon class="mr-2.5 -ml-0.5" size="20px" name="material-symbols:shield-lock-rounded" />
											<span>Безопасность</span>
										</TheClickable>
									</div>
									<div class="mt-3">
										<TheClickable class="menu-item text-[#bf4c44] text-red-5002 flex w-full h-9 rounded-md items-center px-4">
											<DoorIcon class="mr-3 h-4 w-4" />
											Выйти
										</TheClickable>
									</div>
								</div>
							</div>
						</transition>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">

import CerberLogo from '~/assets/svg/cerber-logo.svg'
import TheClickable from '~/components/ui/Clickable.vue'
import GearIcon from '~/assets/svg/Monochrome=gearshape.fill.svg'
import DoorIcon from '~/assets/svg/Monochrome=door.left.hand.open.svg'
import { clickOutside } from '~/utils/helpers'
import { ref, onUnmounted } from '#imports'

const menu = ref(false)
const $menu = ref<HTMLElement>()

const destroyClickOutside = clickOutside($menu, () => menu.value = false).destroy

onUnmounted(() => destroyClickOutside())

</script>

<style lang="scss" scoped>

.top-bar {
	.menu-wrapper {
		&.v-enter-active, &.v-leave-active {
			transition: opacity .15s ease, transform .15s ease;
		}

		&.v-enter-from, &.v-leave-to {
			opacity: 0;
			transform: scale(.95) translateY(7px)
		}
	}

	.menu {
		background-color: darken(#fff, 91%);

		.arrow-top {
			color: darken(#fff, 91%);
		}
	}

	.menu-item {
		&:hover {
			background-color: darken(#fff, 85%);
		}
	}
}

.user-pill {
	transition: background-color .15s ease;

	&:hover, &.menu-is-shown {
		background-color: darken(#fff, 91%);
	}
}

</style>

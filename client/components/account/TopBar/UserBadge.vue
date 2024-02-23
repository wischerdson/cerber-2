<template>
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
				<div class="menu rounded-xl w-64 relative px-2.5 py-2.5 z-50" ref="$menu">
					<div class="absolute right-0 -top-7">
						<icon class="arrow-top" size="48px" name="material-symbols:arrow-drop-up-rounded" />
					</div>
					<div>
						<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4" @click="themeSubmenu = !themeSubmenu">
							<MoonIcon class="mr-3 h-4 w-4" />
							<span>Оформление</span>
							<icon
								class="ml-auto -mr-2 chevron-right"
								:style="{ transform: themeSubmenu ? 'rotate(90deg)': '' }"
								name="material-symbols:chevron-right-rounded"
								size="18px"
							/>
						</TheClickable>
						<HeightAnimation>
							<transition>
								<div class="submenu p-2.5 -mx-2.5 mt-3" v-if="themeSubmenu">
									<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4">
										<SunIcon class="mr-3 h-4 w-4" />
										<span>Светлое</span>
									</TheClickable>
									<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4 relative">
										<div class="absolute left-1.5 rounded-full w-1 h-1 bg-neutral-300"></div>
										<MoonIcon class="mr-3 h-4 w-4" />
										<span>Темное</span>
									</TheClickable>
									<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4">
										<ComputerIcon class="mr-3 h-4 w-4" />
										<span>Как в системе</span>
									</TheClickable>
								</div>
							</transition>
						</HeightAnimation>
					</div>
					<div class="mt-3">
						<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4">
							<GearIcon class="gear-icon mr-3 h-4 w-4" />
							<span>Настройки</span>
						</TheClickable>
					</div>
					<div>
						<TheClickable class="menu-item text-neutral-300 flex w-full h-9 rounded-md items-center px-4">
							<icon class="mr-2.5 -ml-0.5" size="20px" name="material-symbols:shield-rounded" />
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
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import GearIcon from '~/assets/svg/Monochrome=gearshape.fill.svg'
import DoorIcon from '~/assets/svg/Monochrome=door.left.hand.open.svg'
import SunIcon from '~/assets/svg/Monochrome=sun.max.fill.svg'
import MoonIcon from '~/assets/svg/Monochrome=moon.stars.fill.svg'
import ComputerIcon from '~/assets/svg/Monochrome=desktopcomputer.svg'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { clickOutside } from '~/utils/helpers'
import { ref, onUnmounted, watch } from '#imports'

const menu = ref(false)
const themeSubmenu = ref(false)
const $menu = ref<HTMLElement>()

watch(menu, v => !v && (themeSubmenu.value = false))

const destroyClickOutside = clickOutside($menu, () => menu.value = false).destroy

onUnmounted(() => destroyClickOutside())

</script>

<style lang="scss" scoped>

.user-pill {
	transition: background-color .15s ease;

	&:hover, &.menu-is-shown {
		background-color: darken(#fff, 91%);
	}
}

.menu-wrapper {
	&.v-enter-active, &.v-leave-active {
		transition: opacity .15s ease, transform .15s ease;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
		transform: scale(.95) translateY(7px);
	}
}

.menu {
	background-color: darken(#fff, 91%);

	.arrow-top {
		color: darken(#fff, 91%);
	}
}

.menu-item {
	.gear-icon {
		animation: topBarMenuGearSpinning 7s linear infinite;
	}

	.chevron-right {
		transition: transform .15s ease;
	}

	&:hover {
		background-color: darken(#fff, 85%);
	}
}

.submenu {
	background-color: darken(#fff, 93%);

	&.v-leave-active {
		transition: opacity .15s ease;
	}

	&.v-enter-active {
		transition: opacity .25s ease .15s;
	}

	&.v-enter-from, &.v-leave-to {
		opacity: 0;
	}
}

@keyframes topBarMenuGearSpinning {
	from {
		transform: rotate(0);
	}
	to {
		transform: rotate(-360deg);
	}
}

</style>

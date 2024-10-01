<template>
	<div class="relative">
		<UiClickable
			class="user-pill rounded-full pr-2 h-12 flex items-center"
			:class="{ 'menu-is-shown': showMenu }"
			@click="showMenu = !showMenu"
		>
			<div>
				<img class="h-12 rounded-full" src="/images/avatar.jpg" alt="Avatar">
			</div>
			<div class="ml-3">
				<div>
					<span class="text-black/85 dark:text-white/85">{{ userStore.user?.firstName }}&nbsp;</span>
					<span class="text-black/50 dark:text-white/50">{{ lastNameFirstLetter }}.</span>
				</div>
			</div>
			<div class="ml-3">
				<icon class="text-black/50 dark:text-white/50" size="26px" name="material-symbols:arrow-drop-down-rounded" />
			</div>
		</UiClickable>

		<transition>
			<div class="menu-wrapper absolute right-0 top-full pt-4 z-10" v-click-outside="() => showMenu = false" v-show="showMenu">
				<div class="menu rounded-xl w-64 relative px-2.5 py-2.5 z-50" ref="$menu">
					<div class="absolute right-0 -top-7">
						<icon class="arrow-top" size="48px" name="material-symbols:arrow-drop-up-rounded" />
					</div>
					<div>
						<UiClickable class="menu-item dark:text-gray-300 text-gray-800 flex w-full h-9 rounded-md items-center px-4" @click="themeSubmenu = !themeSubmenu">
							<MoonIcon class="mr-3 h-4 w-4" v-if="theme.scheme" />
							<SunIcon class="mr-3 h-4 w-4" v-else />
							<span>Оформление</span>
							<icon
								class="ml-auto -mr-2 chevron-right"
								:style="{ transform: themeSubmenu ? 'rotate(90deg)': '' }"
								name="material-symbols:chevron-right-rounded"
								size="18px"
							/>
						</UiClickable>
						<HeightAnimation>
							<transition>
								<div class="submenu mt-3" v-if="themeSubmenu">
									<UiClickable class="menu-item dark:text-gray-300 text-gray-800 flex w-full h-9 rounded-md items-center px-4" @click="theme.setMode('light')">
										<div class="absolute left-1.5 rounded-full w-1 h-1 dark:bg-gray-300 bg-gray-600" v-if="theme.mode.value === 'light'"></div>
										<SunIcon class="mr-3 h-4 w-4" />
										<span>Светлое</span>
									</UiClickable>
									<UiClickable class="menu-item dark:text-gray-300 text-gray-800 flex w-full h-9 rounded-md items-center px-4 relative" @click="theme.setMode('dark')">
										<div class="absolute left-1.5 rounded-full w-1 h-1 dark:bg-gray-300 bg-gray-600" v-if="theme.mode.value === 'dark'"></div>
										<MoonIcon class="mr-3 h-4 w-4" />
										<span>Темное</span>
									</UiClickable>
									<UiClickable class="menu-item dark:text-gray-300 text-gray-800 flex w-full h-9 rounded-md items-center px-4" @click="theme.setMode('system')">
										<div class="absolute left-1.5 rounded-full w-1 h-1 dark:bg-gray-300 bg-gray-600" v-if="theme.mode.value === 'system'"></div>
										<ComputerIcon class="mr-3 h-4 w-4" />
										<span>Как в системе</span>
									</UiClickable>
								</div>
							</transition>
						</HeightAnimation>
					</div>

					<div class="mt-3">
						<NuxtLink to="settings">
							<UiClickable class="menu-item dark:text-gray-300 text-gray-800 flex w-full h-9 rounded-md items-center px-4">
								<GearIcon class="gear-icon mr-3 h-4 w-4" />
								<span>Настройки</span>
							</UiClickable>
						</NuxtLink>
					</div>
					<div>
						<UiClickable class="menu-item dark:text-gray-300 text-gray-800 flex w-full h-9 rounded-md items-center px-4">
							<icon class="mr-2.5 -ml-0.5" size="20px" name="material-symbols:shield-rounded" />
							<span>Безопасность</span>
						</UiClickable>
					</div>

					<div class="mt-3">
						<UiClickable class="menu-item text-[#bf4c44] flex w-full h-9 rounded-md items-center px-4" @click="logout">
							<DoorIcon class="mr-3 h-4 w-4" />
							<span>Выйти</span>
						</UiClickable>
					</div>
				</div>
			</div>
		</transition>
	</div>
</template>

<script setup lang="ts">

import UiClickable from '~/components/ui/Clickable.vue'
import GearIcon from '~/assets/svg/Monochrome=gearshape.fill.svg'
import DoorIcon from '~/assets/svg/Monochrome=door.left.hand.open.svg'
import SunIcon from '~/assets/svg/Monochrome=sun.max.fill.svg'
import MoonIcon from '~/assets/svg/Monochrome=moon.stars.fill.svg'
import ComputerIcon from '~/assets/svg/Monochrome=desktopcomputer.svg'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { ref, watch, useNuxtApp, onMounted, computed } from '#imports'
import { useAuth } from '~/composables/use-auth'
import { useUserStore } from '~/store/user'
import { useAccountLayoutLoaderStore } from '~/store/loaders'

const userStore = useUserStore()
const loaderStore = useAccountLayoutLoaderStore()

const showMenu = ref(false)
const themeSubmenu = ref(false)
const $menu = ref<HTMLElement>()

const theme = useNuxtApp().$theme
const lastNameFirstLetter = computed(() => userStore.user?.lastName.slice(0, 1).toUpperCase())

watch(showMenu, () => themeSubmenu.value = false)

const logout = () => useAuth('default').logout()

loaderStore.addPromise(new Promise<void>(resolve => {
	onMounted(async () => {
		await userStore.fetch()
		resolve()
	})
}))

</script>

<style lang="scss" scoped>

@use "sass:color";

.user-pill {
	transition: background-color .15s ease;

	&:hover, &.menu-is-shown {
		background-color: #ebebeb;
	}
}

html.dark {
	.user-pill {
		&:hover, &.menu-is-shown {
			background-color: color.scale(#fff, $lightness: -85%);
		}
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
	box-shadow: 0 0 20px 0 rgba(#000, 8%);
	background-color: #fff;

	.arrow-top {
		color: #fff;
	}
}

html.dark {
	.menu {
		background-color: color.scale(#fff, $lightness: -91%);

		.arrow-top {
			color: color.scale(#fff, $lightness: -91%);
		}
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
		background-color: #ebebeb;
	}
}

html.dark {
	.menu-item {
		&:hover {
			background-color: color.scale(#fff, $lightness: -85%);
		}
	}
}

.submenu {
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

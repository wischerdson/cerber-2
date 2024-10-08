<template>
	<div class="min-h-screen relative">
		<TopBar />
		<ScreenSize />
		<slot></slot>

		<div class="fixed inset-0 z-40 flex items-center justify-center dark:bg-black bg-white" v-if="loaderStore.showLoader">
			<TheSpinner class="relative z-10" size="38" />
			<div class="absolute bottom-0 -z-10 inset-x-0 pointer-events-none flex justify-center">
				<!-- <img class="w-full dark:opacity-10 opacity-20 -z-10" src="/images/blurs-footer.png"> -->
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">

import { onMounted, useHead, useNuxtApp } from '#imports'
import ScreenSize from '~/components/dev/ScreenSize.vue'
import TopBar from '~/components/account/TopBar.vue'
import TheSpinner from '~/components/ui/Spinner.vue'
import { useNoindexHeader } from '~/composables/use-noindex-header'
import { useAccountLayoutLoaderStore } from '~/store/loaders'

useNoindexHeader()

useHead({
	htmlAttrs: {
		class: useNuxtApp().$theme.scheme,
		lang: 'ru-RU'
	},
	link: [
		{ rel: 'apple-touch-icon', sizes: '180x180', href: '/favicon/apple-touch-icon.png' },
		{ rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon/favicon-32x32.png' },
		{ rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon/favicon-16x16.png' },
		{ rel: 'manifest', href: '/favicon/site.webmanifest' },
		{ rel: 'mask-icon', href: '/favicon/safari-pinned-tab.svg', color: '#d5aa4f' },
		{ rel: 'shortcut icon', href: '/favicon/favicon.ico' },
	],
	meta: [
		{ name: 'msapplication-TileColor', content: '#000000' },
		{ name: 'msapplication-config', content: '/favicon/browserconfig.xml' },
		{ name: 'theme-color', content: '#ffffff' }
	],
	script: [
		{ src: '/particles.min.js' }
	]
})

const loaderStore = useAccountLayoutLoaderStore()

loaderStore.addPromise(new Promise<void>(resolve => {
	onMounted(resolve)
}))

</script>

<style lang="scss">

body {
	@apply text-black dark:text-white;

	font-family: theme('fontFamily.sans');
	background-color: #fbfbfd;
}

html.dark body {
	background-color: #000;
}

html.theme-changing * {
	transition: all .15s ease;
}

</style>

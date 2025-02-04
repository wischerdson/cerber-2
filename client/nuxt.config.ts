import svgLoader from 'vite-svg-loader'
import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	compatibilityDate: '2024-11-01',
	devtools: { enabled: false },
	vite: {
		plugins: [
			svgLoader({ defaultImport: 'component' }),
			tailwindcss()
		]
	},
	css: [
		'~/assets/css/fonts.css',
		'~/assets/css/tailwind.css'
	],
	components: false,
	app: {
		rootId: 'app',
		buildAssetsDir: process.env.NODE_ENV === 'production' ? '/assets/' : void 0,
	},
	imports: {
		autoImport: false
	},
	modules: [
		'nuxt-icon',
		'@pinia/nuxt'
	]
})

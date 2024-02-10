import svgLoader from 'vite-svg-loader'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	devtools: { enabled: false },

	components: false,

	app: {
		rootId: 'app',
		buildAssetsDir: process.env.NODE_ENV === 'production' ? '/assets/' : void 0,
	},

	imports: {
		autoImport: false
	},

	runtimeConfig: {
		public: {
			storageBaseUrl: process.env.STORAGE_URL,
			apiBaseUrl: process.env.CLIENT_API_URL
		},
		apiBaseUrl: process.env.SERVER_API_URL
	},

	modules: [
		'@nuxtjs/tailwindcss',
		'nuxt-icon'
	],

	tailwindcss: {
		exposeConfig: false
	},

	features: {
		inlineStyles: false
	},

	css: [
		'~/assets/sass/fonts.scss',
		'~/assets/sass/reset.scss'
	],

	vite: {
		plugins: [svgLoader({ defaultImport: 'component' })]
	}
})

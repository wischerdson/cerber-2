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
			apiBase: process.env.BROWSER_API_URL
		},
		apiBase: process.env.SERVER_API_URL
	},

	modules: [
		'@nuxtjs/tailwindcss',
		'nuxt-icon'
	],

	tailwindcss: {
		exposeConfig: false,
		injectPosition: 'last'
	},

	features: {
		inlineStyles: false
	},

	css: [
		'~/assets/sass/fonts.scss',
		'~/assets/sass/reset.scss',
	],

	vite: {
		plugins: [svgLoader({ defaultImport: 'component' })]
	}
})
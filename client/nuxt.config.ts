import svgLoader from 'vite-svg-loader'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	devtools: { enabled: false },

	components: false,

	app: {
		rootId: 'app'
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
		cssPath: '~/assets/sass/tailwind.scss',
		exposeConfig: false,
		injectPosition: 'last'
	},

	vite: {
		plugins: [svgLoader({ defaultImport: 'component' })]
	}
})

import svgLoader from 'vite-svg-loader'
import { resolve } from 'path'

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
		'nuxt-icon'
	],

	postcss: {
		plugins: {
			tailwindcss: {
				config: resolve(__dirname, './tailwind.config.ts')
			},
			autoprefixer: {},
		},
	},

	features: {
		inlineStyles: false
	},

	css: [
		'~/assets/sass/fonts.scss',
		'~/assets/sass/reset.scss',
		'~/assets/sass/tailwind.css',
		'~/assets/sass/utils.scss'
	],

	vite: {
		plugins: [svgLoader({ defaultImport: 'component' })]
	}
})

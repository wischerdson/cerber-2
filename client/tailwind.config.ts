import type { Config } from 'tailwindcss'
import { resolve } from 'path'

const config: Config = {
	darkMode: 'selector',
	content: [
		resolve(__dirname, './components/**/*.{js,vue,ts}'),
		resolve(__dirname, './layouts/**/*.vue'),
		resolve(__dirname, './pages/**/*.vue'),
		resolve(__dirname, './plugins/**/*.{js,ts}'),
		resolve(__dirname, './error.vue')
	],
	corePlugins: {
		preflight: false,
		container: false,
		boxShadow: false
	},
	theme: {
		extend: {
			colors: {
				primary: '#933131',
				dark: {
					'gray-1': '#a3a3a3',
					'gray-2': '#0c0c0c',
					'gray-3': '#1d1d1d',
				},
				'red-1': '#933131',
				gray: {
					950: '#0d0d0d',
					900: '#1a1a1a',
					850: '#262626',
					800: '#333333',
					750: '#404040',
					700: '#4d4d4d',
					650: '#595959',
					600: '#666666',
					550: '#737373',
					500: '#808080',
					450: '#8c8c8c',
					400: '#999999',
					350: '#a6a6a6',
					300: '#b3b3b3',
					250: '#bfbfbf',
					200: '#cccccc',
					150: '#d9d9d9',
					100: '#e6e6e6',
					50:  '#f2f2f2'
				}
			},
			padding: {
				'1/3': '33.333333%',
				'2/3': '66.666667%',
				'1/4': '25%',
				'2/4': '50%',
				'3/4': '75%',
				'1/5': '20%',
				'2/5': '40%',
				'3/5': '60%',
				'4/5': '80%',
				'1/6': '16.666667%',
				'2/6': '33.333333%',
				'3/6': '50%',
				'4/6': '66.666667%',
				'5/6': '83.333333%',
				'1/12': '8.333333%',
				'2/12': '16.666667%',
				'3/12': '25%',
				'4/12': '33.333333%',
				'5/12': '41.666667%',
				'6/12': '50%',
				'7/12': '58.333333%',
				'8/12': '66.666667%',
				'9/12': '75%',
				'10/12': '83.333333%',
				'11/12': '91.666667%',
				'full': '100%'
			},
			maxWidth: {
				macbook13: '1470px',
			}
		},
		fontFamily: {
			sans: 'Roboto, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
			mono: 'JetBrainsMono, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace'
		},
		screens: {
			'2xl': { 'max': '1535px' },
			'xl':  { 'max': '1279px' },
			'lg':  { 'max': '1023px' },
			'md':  { 'max': '767px' },
			'sm':  { 'max': '639px' },
		},
		fontSize: {
			'2xs':   ['0.5rem', '1'],
			'xs':    ['0.75rem', '1'],
			'sm':    ['0.875rem', '1'],
			'base':  ['1rem', '1'],
			'lg':    ['1.125rem', '1'],
			'xl':    ['1.25rem', '1'],
			'2xl':   ['1.5rem', '1'],
			'2.5xl': ['1.6575rem', '1'],
			'3xl':   ['1.875rem', '1'],
			'4xl':   ['2.25rem', '1'],
			'5xl':   ['3rem', '1'],
			'6xl':   ['3.75rem', '1'],
			'7xl':   ['4.5rem', '1'],
			'8xl':   ['6rem', '1'],
			'9xl':   ['8rem', '1']
		}
	}
}

export default config

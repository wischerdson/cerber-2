<template>
	<div v-uid ref="$spoiler"></div>
</template>

<script setup lang="ts">

import { ref, onMounted } from 'vue'

const $spoiler = ref<HTMLElement>()

const config = {
	particles: {
		number: {
			value: 500,
			density: {
				enable: false,
				value_area: 800
			}
		},
		color: { value: '#000000' },
		shape: {
			type: 'circle',
			stroke: {
				width: 0,
				color: '#000000'
			},
			polygon: { nb_sides: 5 }
		},
		opacity: {
			value: 1,
			random: false
		},
		size: {
			value: 1,
			random: false,
			anim: {
				enable: false,
				speed: 40,
				size_min: 0.1,
				sync: false
			}
		},
		line_linked: { enable: false },
		move: {
			enable: true,
			speed: 0.6,
			direction: 'none',
			random: false,
			straight: false,
			out_mode: 'out',
			bounce: false,
			attract: {
				enable: false,
				rotateX: 600,
				rotateY: 1200
			}
		}
	},
	interactivity: {
		detect_on: 'canvas',
		events: {
			onhover: {
				enable: false,
				mode: 'repulse'
			},
			onclick: {
				enable: false,
				mode: 'push'
			},
			resize: true
		}
	},
	retina_detect: true
}

onMounted(() => {
	if (!('particlesJS' in window)) {
		console.error('particlesJS is not defined')
	}

	// @ts-ignore
	window.particlesJS($spoiler.value.id, config)
})

</script>

<template>
	<div :id="id"></div>
</template>

<script setup lang="ts">

import { onMounted } from 'vue'
import { useId } from 'vue'

const id = useId()

const config = {
	particles: {
		number: {
			value: 400,
			density: {
				enable: false,
				value_area: 800
			}
		},
		color: { value: '#000' },
		shape: {
			type: 'circle',
			stroke: {
				width: 0,
				color: '#000000'
			},
			polygon: { nb_sides: 0 }
		},
		opacity: {
			anim: {
				enable: true,
				speed: 2,
				opacity_min: 0.5
			},
			value: 1,
			random: true
		},
		size: {
			value: 1.4,
			random: false,
			anim: {
				enable: true,
				speed: 3,
				size_min: 0.1,
				sync: false
			}
		},
		line_linked: { enable: false },
		move: {
			enable: true,
			speed: 0.3,
			direction: 'none',
			random: true,
			straight: false,
			out_mode: 'bounce',
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
	window.particlesJS(id, config)
})

</script>

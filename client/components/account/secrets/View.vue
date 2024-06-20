<template>
	<div class="secret-view">
		<div>
			<h2 class="font-bold text-gray-850 text-1.5xl tracking-wide">База данных</h2>
			<p class="mt-4 leading-tight tracking-wide text-gray-700 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
		</div>
		<div class="mt-8 space-y-4">
			<div>
				<div class="form-label">URL</div>
				<div class="relative rounded-lg py-3 px-3 bg-gray-50">
					<span>https://sk-dev.ru/phpmyadmin</span>
					<div class="absolute inset-y-0 flex items-center right-0 pr-3">
						<icon class="text-gray-500" name="material-symbols:content-copy-rounded" size="20px" />
					</div>
				</div>
			</div>

			<div>
				<div class="form-label">Логин</div>
				<div class="relative rounded-lg py-3 px-3 bg-gray-50">
					<span>panteric</span>
					<div class="absolute inset-y-0 flex items-center right-0 pr-3">
						<icon class="text-gray-500" name="material-symbols:content-copy-rounded" size="20px" />
					</div>
					<div class="absolute inset-y-0 flex items-center right-0 pr-3 select-none">
						<div class="text-xs tracking-wide bg-green-200 text-green-700 px-2 py-1 rounded-full">Скопировано</div>
					</div>
				</div>
			</div>

			<div class="pt-5">
				<div class="form-label -mt-5">Пароль</div>
				<div class="flex">
					<div class="relative flex-1 rounded-lg  py-3 px-3 bg-gray-50 overflow-hidden">
						<span class="opacity-0">panteric</span>
						<div class="absolute inset-0 pr-8 p-0.5">
							<div class="w-full h-full" id="particles"></div>
						</div>
						<div class="absolute inset-0 z-10 right-8" style="box-shadow: inset 0 0 5px 12px #f2f2f2;"></div>
						<div class="absolute inset-y-0 z-20 flex items-center right-0 pr-3">
							<icon class="text-gray-500" name="material-symbols:content-copy-rounded" size="20px" />
						</div>
					</div>
					<TheClickable class="w-10 flex items-center justify-center self-stretch">
						<EyeIcon class="w-5 text-gray-500" />
					</TheClickable>
				</div>
			</div>

			<!-- <TextField value="https://sk-dev.ru/phpmyadmin">
				<template #label>URL</template>
			</TextField>
			<TextField value="panteric">
				<template #label>Логин</template>
			</TextField>
			<TextField value="dafasdfasdf" type="password">
				<template #label>Пароль</template>
			</TextField>
			 -->
		</div>
	</div>
</template>

<script setup lang="ts">

import TextField from '~/components/ui/TextField.vue'
import InputText from '~/components/ui/input/Text.vue'
import TextArea from '~/components/ui/input/TextArea.vue'
import Labeled from '~/components/ui/labeled-input/Labeled.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import EyeSlashIcon from '~/assets/svg/Monochrome=eye.slash.fill.svg'
import EyeIcon from '~/assets/svg/Monochrome=eye.fill.svg'
import { ref, onMounted } from 'vue'

onMounted(() => {



	window.particlesJS('particles', {
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
	})


})



const phone = ref('')

</script>

<style scoped lang="scss">

.secret-view {

}

</style>

<template>
	<div class="secret-list" :class="{ shown }">
		<TheClickable class="show-hide-btn flex items-center justify-center text-gray-700 ml-1" @click="shown = !shown">
			<span class="text-sm">Доступы</span>
			<icon
				class="chevron-right -mb-0.5 ml-1 -mr-2"
				name="material-symbols:chevron-right-rounded"
				size="22px"
			/>
		</TheClickable>
		<HeightAnimation>
			<transition name="list">
				<ul class="mt-2.5" v-if="shown">
					<li>
						<SecretItem name="База данных" :icon="{ type: 'icones', value: 'material-symbols:database' }" />
					</li>
					<li>
						<SecretItem name="SSH" :icon="{ type: 'icones', value: 'material-symbols:terminal-rounded' }" />
					</li>
					<li>
						<SecretItem name="Админка" />
					</li>
					<li>
						<SecretItem name="panteric" :icon="{ type: 'img', value: 'https://panteric.ru/files/gallery/58/apple_180x180/panteric_1717161911.png' }" />
					</li>
					<li>
						<SecretItem name="odekolon" :actual="false" :icon="{ type: 'img', value: 'https://odekolon-shop.ru/image/catalog/4-100x100.png' }" />
					</li>
					<li>
						<SecretItem name="Apple" :icon="{ type: 'img', value: 'https://www.apple.com/favicon.ico' }" />
					</li>
					<li>
						<SecretItem name="ftp" :icon="{ type: 'icones', value: 'mdi:folder-network-outline' }" />
					</li>
				</ul>
			</transition>
		</HeightAnimation>
	</div>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import SecretItem from '~/components/account/secrets/list/SecretItem.vue'
import HeightAnimation from '~/components/ui/HeightAnimation.vue'
import { ref } from 'vue'

const shown = ref(true)

</script>

<style scoped lang="scss">

.secret-list {
	.show-hide-btn {
		svg {
			transition: transform .15s ease;
		}
	}

	&.shown {
		.show-hide-btn {
			svg {
				transform: rotate(90deg);
			}
		}
	}
}

.list-leave-active {
	transition: opacity .15s ease;
}

.list-enter-active {
	transition: opacity .25s ease .15s;
}

.list-enter-from, .list-leave-to {
	opacity: 0;
}

</style>

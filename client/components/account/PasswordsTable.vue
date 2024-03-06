<template>
	<ul class="passwords-list">
		<li class="password-item relative" v-for="(password, idx) in passwords" :key="`password-${idx}`">
			<TheClickable class="flex w-full py-2 ">
				<div>
					<div class="w-10 h-10" v-if="password.icon.type === 'image'">
						<img class="w-full h-full object-contain object-center rounded-md" :src="password.icon.content">
					</div>
					<div class="w-10 h-10" v-else-if="password.icon.type === 'icones'">
						<icon class="text-gray-200" size="28px" :name="password.icon.content" />
					</div>
					<div class="w-10 h-10 p-1 bg-gray-850 rounded-lg flex items-center justify-center" v-else-if="password.icon.type === 'letter'">
						<span class="text-2xl text-gray-200" >E</span>
					</div>
				</div>
				<div class="ml-4 text-left">
					<div>{{ password.name }}</div>
					<div class="text-sm text-gray-600 font-light mt-1.5">
						<span>{{ password.login }}</span>
					</div>
				</div>
				<div class="ml-auto flex">
					<div class="flex items-center mr-6">
						<div class="text-xs text-gray-700">
							12 февраля 2024 г
						</div>
					</div>
					<div class="flex items-center">
						<div v-if="password.starred">
							<icon class="text-gray-100 -mt-0.5" size="26px" name="material-symbols:star-rounded"  />
						</div>
						<div v-else>
							<icon class="text-gray-500 -mt-0.5" size="26px" name="material-symbols:star-outline-rounded" />
						</div>
					</div>
				</div>
			</TheClickable>
			<hr class="absolute bottom-0 left-14 right-0 border-gray-900">
		</li>
	</ul>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'

type Password = {
	name: string
	login: string
	starred: boolean
	icon: {
		type: 'image' | 'icones' | 'raw-svg' | 'letter'
		content: string
	}
	created_at: string
}

const props = defineProps<{ passwords: Password[] }>()

</script>

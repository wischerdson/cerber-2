<template>
	<UiBaseModal v-model="show">
		<div v-bind="useAttrs()" class="bg-white relative p-6 lg:px-4 rounded-2xl lg:rounded-none overflow-auto">
			<button
				class="btn w-10 h-10 p-0 z-10 absolute lg:fixed right-0 top-0 opacity-50 transition-opacity hover:opacity-100"
				@click="show = false"
				title="Закрыть"
			>
				<icon name="material-symbols:close-rounded" size="22px" />
			</button>
			<slot></slot>
		</div>
	</UiBaseModal>
</template>

<script setup lang="ts">

import { useAttrs } from 'vue'
import UiBaseModal from '~/components/ui/BaseModal.vue'

const show = defineModel<boolean>({ required: true })

</script>

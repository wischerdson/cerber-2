<template>
	<DefaultPageView>
		<!-- <div class="flex justify-between">
			<h1 class="font-bold text-gray-900 dark:text-gray-100 text-2.5xl">Доступы</h1>
			<div>
				<TheClickable
					class="add-password-btn bg-gray-100 text-black flex items-center justify-center h-10 px-4 rounded-full"
				>
					<icon class="mr-2" size="24px" name="material-symbols:add-rounded" />
					<span>Добавить</span>
				</TheClickable>
			</div>
		</div> -->

		<div class="flex items-start gap-6 mt-4">
			<Sidebar />
			<ContentTile class="w-full h-52"> </ContentTile>
			<ContentTile class="w-full">
				<SecretView />
			</ContentTile>
		</div>
	</DefaultPageView>
</template>

<script setup lang="ts">

import DefaultPageView from '~/components/ui/DefaultPageView.vue'
import ContentTile from '~/components/ui/ContentTile.vue'
import TheClickable from '~/components/ui/Clickable.vue'
import TextField from '~/components/ui/TextField.vue'
import TextArea from '~/components/ui/TextArea.vue'
import Sidebar from '~/components/account/LeftSidebarWithGroups.vue'
import GearIcon from '~/assets/svg/Monochrome=gearshape.fill.svg'
import SecretView from '~/components/account/secrets/View.vue'
import { definePageMeta, useHead } from '#imports'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

<style scoped lang="scss"></style>

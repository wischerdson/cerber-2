<template>
	<DefaultPageView>
		<div class="flex items-start gap-6 mt-4">
			<Sidebar />
			<ContentTile class="w-full">
				<div>
					<SecretList />
				</div>

				<hr class="my-5 border-gray-100">

				<div>
					<TheClickable class="flex items-center justify-center h-9 text-gray-700 px-4 rounded-full -ml-3">
						<span class="text-sm">Группы</span>
						<icon
							class="chevron-right -mb-0.5 ml-1 -mr-2 rotate-90"
							name="material-symbols:chevron-right-rounded"
							size="22px"
						/>
					</TheClickable>
					<ul class="">
						<li class="secret-item flex items-center space-x-3 rounded-lg py-1.5 px-2 -mx-1.5">
							<FolderIcon class="w-6 h-6" />
							<div>
								<div>
									<span>panteric</span>
								</div>
							</div>
						</li>
						<li class="secret-item flex items-center space-x-3 rounded-lg py-1.5 px-2 -mx-1.5">
							<FolderIcon class="w-6 h-6" />
							<div>
								<div>
									<span>odekolon-shop</span>
								</div>
							</div>
						</li>
						<li class="secret-item flex items-center space-x-3 rounded-lg py-1.5 px-2 -mx-1.5">
							<FolderIcon class="w-6 h-6" />
							<div>
								<div>
									<span>smolsk</span>
								</div>
							</div>
						</li>
						<li class="secret-item flex items-center space-x-3 rounded-lg py-1.5 px-2 -mx-1.5">
							<FolderIcon class="w-6 h-6" />
							<div>
								<div>
									<span>pstroybaza</span>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</ContentTile>
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
import FolderIcon from '~/assets/svg/Colored=folder.fill.svg'
import SecretView from '~/components/account/secrets/View.vue'
import SecretList from '~/components/account/secrets/list/SecretList.vue'
import { definePageMeta, useHead } from '#imports'

definePageMeta({ middleware: 'auth' })

useHead({ title: 'Cerber - Доступы' })

</script>

<style scoped lang="scss">

.a {}

</style>

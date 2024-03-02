<template>
	<div class="spaces-switch bg-gray-50/50 rounded-full p-1 relative">
		<div
			class="highlighter rounded-full absolute inset-y-1 left-0 w-20 z-0"
			:style="{
				transition: highlighter.transition ? '' : 'none',
				transform: `translateX(${highlighter.x}px)`,
				width: `${highlighter.width}px`
			}"
		></div>
		<ul class="flex relative z-10">
			<li>
				<button class="switch-item h-[42px] flex items-center px-5 rounded-full" data-space="private" @click="changeSpace('private')">
					<icon class="mr-2.5" name="material-symbols:person-rounded" size="24px" />
					<span>Личное</span>
				</button>
			</li>
			<li>
				<button class="switch-item h-[42px] flex items-center px-5" data-space="work" @click="changeSpace('work')">
					<BriefcaseIcon class="w-5 h-5 mr-3" />
					<span>Работа</span>
				</button>
			</li>
			<li>
				<TheClickable class="switch-item border border-gray-200 h-[42px] flex justify-center items-center w-[42px] rounded-full">
					<!-- <icon size="24px" name="material-symbols:add-rounded" /> -->
					<icon size="20px" name="ion:ellipsis-horizontal" />
				</TheClickable>
			</li>
		</ul>
	</div>
</template>

<script setup lang="ts">

import TheClickable from '~/components/ui/Clickable.vue'
import { getCurrentInstance, onMounted, ref } from '#imports'
import BriefcaseIcon from '~/assets/svg/Monochrome=briefcase.fill.svg'

const highlighter = ref({ x: 0, width: 0, transition: false })
const $root = getCurrentInstance()?.vnode.el as HTMLElement

const changeSpace = (space: string) => {
	moveHighlighter(space)
}

const moveHighlighter = (space: string) => {
	const $switchItem = $root?.querySelector(`[data-space="${space}"]`)

	if (!$switchItem) {
		return
	}

	const itemBounds = $switchItem.getBoundingClientRect()
	const rootBounds = $root.getBoundingClientRect()

	highlighter.value.width = itemBounds.width
	highlighter.value.x = itemBounds.x - rootBounds.x
}

onMounted(() => {
	moveHighlighter('private')
	setTimeout(() => highlighter.value.transition = true, 100)
})

</script>

<style scoped lang="scss">

.switch-item {
	transition: color .15s ease;
}

.highlighter {
	transition: transform .4s cubic-bezier(.3,.3,.14,1), width .4s cubic-bezier(.3,.3,.14,1);
}

.highlighter, .switch-item.active {
	box-shadow: 0 0 6px 0 rgba(#000, 12%);
	background-color: #fff;
}

</style>

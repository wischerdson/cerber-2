<template>
	<div class="spaces-switch p-1 relative" ref="$root">
		<div
			class="highlighter bg-white dark:bg-white/15 rounded-full absolute inset-y-1 left-0 z-0"
			:style="{
				transition: highlighter.transition ? '' : 'none',
				transform: `translateX(${highlighter.x}px)`,
				width: `${highlighter.width}px`
			}"
		></div>
		<ul class="flex relative z-10">
			<li v-for="space in spaces" :key="space.clientCode">
				<button
					class="switch-item h-[42px] flex items-center px-5"
					:class="{ active: space.clientCode === activeSpace }"
					:data-space="space.clientCode"
					@click="activeSpace = space.clientCode"
				>
					<icon class="mr-1.5" :name="space.icon" size="20px" />
					<span>{{ space.name }}</span>
				</button>
			</li>
			<li class="ml-3">
				<UiClickable
					class="show-more-btn bg-white dark:bg-transparent dark:text-gray-250 h-[42px] flex justify-center items-center w-[42px] rounded-full"
					@click=""
				>
					<icon size="24px" name="material-symbols:add-rounded" v-if="false"/>
					<icon size="20px" name="ion:ellipsis-horizontal" />
				</UiClickable>
			</li>
		</ul>
	</div>
</template>

<script setup lang="ts">

import UiClickable from '~/components/ui/Clickable.vue'
import { onMounted, ref, watch } from '#imports'

const highlighter = ref({ x: 0, width: 0, transition: false })
const activeSpace = ref(1)
const $root = ref<HTMLElement>()

const spaces = ref([
	{ clientCode: 1, name: 'Личное', icon: 'material-symbols:person-rounded' },
	{ clientCode: 2, name: 'Коммерческие проекты', icon: 'material-symbols:attach-money-rounded' },
	{ clientCode: 3, name: 'Компания', icon: 'material-symbols:person-rounded' },
])

watch(activeSpace, space => moveHighlighter(space))

const moveHighlighter = (space: string | number) => {
	const $switchItem = $root.value?.querySelector(`[data-space="${space}"]`)

	if (!$switchItem) {
		return
	}

	const itemBounds = $switchItem.getBoundingClientRect()
	const rootBounds = ($root.value as HTMLElement).getBoundingClientRect()

	highlighter.value.width = itemBounds.width
	highlighter.value.x = itemBounds.x - rootBounds.x
}

onMounted(() => {
	moveHighlighter(1)
	setTimeout(() => highlighter.value.transition = true, 100)
})

</script>

<style scoped lang="scss">

.switch-item {
	transition: color .2s ease;
	color: theme('colors.gray.800');

	&.active {
		color: #000;
	}
}

.highlighter {
	transition: transform .4s cubic-bezier(.3,.3,.14,1), width .4s cubic-bezier(.3,.3,.14,1);
	box-shadow: 0 0 6px 0 rgba(#000, 12%);
}

.show-more-btn {
	box-shadow: 0 0 6px 0 rgba(#000, 6%);
}

html.dark {
	.switch-item {
		color: rgba(#fff, .7);

		&.active {
			color: #fff;
		}
	}

	.show-more-btn {
		border: 1px solid theme('colors.gray.800')
	}
}

</style>

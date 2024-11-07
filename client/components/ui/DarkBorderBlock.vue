<template>
	<div class="ui-dark-border-block">
		<slot></slot>
	</div>
</template>

<style lang="scss">

.ui-dark-border-block:before {
	content: "";
	position: absolute;
	inset: -1px;
	z-index: -1;
	background: linear-gradient(145deg, rgba(#fff, .2), rgba(#fff, .075), rgba(#fff, .05));
	padding: 1px;
	border-radius: inherit;
	mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#862e2e 0 0);
	-webkit-mask-composite: xor;
	mask-composite: exclude;
	pointer-events: none;
	opacity: 0;
}

html.dark .ui-dark-border-block {
	background-color: #000;
	background-image: linear-gradient(145deg, rgba(#fff, .05), rgba(#fff, 0.025), rgba(#fff, 0.04));
	box-shadow: none;

	@supports (backdrop-filter: blur(18px)) {
		background-color: rgba(#000, .4);
		backdrop-filter: blur(18px) saturate(1.5);
	}

	&:before {
		opacity: 1;
	}
}

</style>

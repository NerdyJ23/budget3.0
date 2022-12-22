<template>
	<v-row class="d-flex d-flex-column align-center">
		<v-col
			@mouseover="$emit('hovered', label); hovered = true"
			@mouseleave="$emit('leave', label); hovered = false"
			@click="$emit('toggleVisibility', label)"
			class="legend-label-color"
			cols="4"
			:style="`background-color: ${color};`">
		</v-col>
		<v-col
			cols="8"
			:class="[{'text-decoration-line-through': !visible}]"
		>
			{{ label }}
			<span :style="`${hovered ? '' : 'visibility: hidden'}`">${{ price.toFixed(2) }}</span>
		</v-col>
	</v-row>
</template>
<script>
export default {
	name: "GraphLegendItem",
	props: {
		color: {
			type: String,
			required: true
		},
		label: {
			type: String,
			required: true
		},
		price: {
			type: Number,
			required: true
		},
		visible: {
			type: Boolean,
			required: false,
			default: true
		}
	},
	data() {
		return {
			hovered: false
		}
	},
	methods: {
		test() {
			console.log("test")
		}
	}
}
</script>
<style scoped>
.legend-label-color{
	max-height: 2px;

	min-width: 24px;
	max-width: 15%;
	transition: all 1s;
}
</style>
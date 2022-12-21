<template>
	<v-row class="d-flex d-flex-column align-center">
		<v-col
			@mouseover="$emit('hovered', label)"
			@mouseleave="$emit('leave', label)"
			@click="$emit('toggleVisibility', label)"
			class="pa-2 legend-label-color"
			cols="4"
			:style="`background-color: ${color};`">
		</v-col>
		<v-col
			cols="8"
			:class="[{'text-decoration-line-through': !visible}]"
		>
			{{ label }}
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
		visible: {
			type: Boolean,
			required: false,
			default: true
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
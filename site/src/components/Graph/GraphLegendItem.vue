<template>
	<v-row @hover="$emit('hovered')">
		<v-col cols="4" :style="`background-color: ${color}`"></v-col>
		<v-col cols="8">{{ label }}</v-col>
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
		}
	}
}
</script>
<template>
	<v-card>
		<v-card-title>Legend</v-card-title>
		<v-card-text>
			<v-row>
				<v-col cols="4" v-for="item of items">
					<GraphLegendItem :label="item.label" :color="item.color" />
				</v-col>
			</v-row>
		</v-card-text>
		<v-card-actions></v-card-actions>
	</v-card>
</template>
<script>
import GraphLegendItem from "./GraphLegendItem";

export default {
	name: "GraphLegend",
	components: {
		GraphLegendItem
	},
	props: {
		items: {
			type: Array,
			required: false,
			default: () => {
				return [];
			}
		}
	}
}
</script>
<template>
	<v-card>
		<v-card-title>Legend</v-card-title>
		<v-card-text>
			<v-row>
				<v-col cols="4" v-for="item of items" class="py-4">
					<GraphLegendItem :label="item.label" :color="item.color"
						@hovered="(n) => $emit('hovered', n)"
						@leave="(n) => $emit('leave', n)"
					/>
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
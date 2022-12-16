<template>
	<v-list
		class="rounded-0 elevation-1 fill-height"
		:style="`width: ${active ? 20 : 0}vw`"
	>
		<v-subheader>Menu</v-subheader>
		<v-list-item-group
			v-model="selectedItem"
		>
			<v-list-item to="/">
				<v-list-item-icon>
					<v-icon>mdi-home</v-icon>
				</v-list-item-icon>
				<v-list-item-content>
					Home
				</v-list-item-content>
			</v-list-item>

			<v-list-item to="/receipt">
				<v-list-item-icon>
					<v-icon>mdi-receipt-text</v-icon>
				</v-list-item-icon>
				<v-list-item-content>
					Receipts
				</v-list-item-content>
			</v-list-item>
			<v-list-item to="/graph">
				<v-list-item-icon>
					<v-icon>mdi-chart-line-variant</v-icon>
				</v-list-item-icon>
				<v-list-item-content>
					Graphs
				</v-list-item-content>
			</v-list-item>
		</v-list-item-group>
	</v-list>
</template>
<script>
import { mapState } from "vuex";

export default {
	data() {
		return {
			active: false,
			selectedItem: 0,
		}
	},
	methods: {
		toggle() {
			this.active = !this.active;
			this.GenericStore.drawerExpanded = this.active;
		}
	},
	computed: {
		...mapState(["GenericStore"])
	}
}
</script>
<style scoped>
	.expandedDrawer {
		width: 15vw;
	}
</style>
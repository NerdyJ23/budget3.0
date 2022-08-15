<template>
<div>
	<v-card class="mx-6">
		<v-card-title>
		<v-btn to="/receipt" icon class="ma-2">
			<v-icon>mdi-keyboard-backspace</v-icon>
		</v-btn>
		{{receipt.name}}</v-card-title>
		<v-card-text>
			<v-divider class="mb-4"></v-divider>
			<v-row v-for="item in receipt.items">
				<v-col cols="1">{{item.count}}x </v-col>
				<v-col cols="9">{{item.name}}</v-col>
				<v-col cols="2" class="pr-4 text-right font-weight-bold">${{(item.cost*item.count).toFixed(2)}}</v-col>
			</v-row>

			<v-row>
				<v-col cols="12">
					<v-divider></v-divider>
				</v-col>
			</v-row>

			<v-row>
				<v-col cols="12" class="text-right">
					<span class="text-h6 font-weight-black">Total: ${{totalCost}}</span>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</div>
</template>

<script>
export default {
	mounted() {
		this.loadItems();
	},
	data: function() {
		return {
			apiUrl: this.$store.state.api,
			receipt: null
		}

	},
	methods: {
		loadItems() {
			fetch(`${this.apiUrl}/receipt/${this.$route.params.receiptid}`, {
				method: 'GET',
			}).then(response => {
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				console.log(data);
				this.receipt = data.result;
				this.$store.commit('setToken',data.csrfToken);
				// this.$store.state.csrfToken = data.csrfToken;
			}).catch(err => {
				console.error(err);
			})
		}
	},
	computed: {
		totalCost() {
			let cost = 0;

			for(let a in this.receipt.items) {
				cost += this.receipt.items[a].cost * this.receipt.items[a].count;
			}
			return cost;
		}
	}
}
</script>
<template>
	<v-card>
		<v-card-title>Receipts List</v-card-title>
		<v-card-text>
			<div v-for="receipt in receipts">
				<v-list-item>
					<v-list-item-content>
						<v-list-item-title>
							<v-btn :to="link" text>
								${{receipt.cost}} - {{receipt.name}}
							</v-btn>
						</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</div>
		</v-card-text>
	</v-card>
</template>

<script>
import Receipt from '../components/Receipt';

export default {
	components: {
		Receipt
	},
	mounted() {
		this.loadReceipts();
	},
	data: function() {
		return {
			receipts: [],
			apiUrl: this.$store.state.api
		}
	},
	methods: {
		loadReceipts() {
			fetch(`${this.apiUrl}/receipt?month=5&year=2021`, {
				method: 'GET',
			}).then(response => {
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				console.log(data);
				this.receipts = data.result;
				this.loaded = true;
			}).catch(err => {
				console.error(err);
				this.loaded = true;
			})
		}
	},
	computed: {
		link() {
			return `/receipt/${this.receipt.id}`;
		}
	}
}
</script>
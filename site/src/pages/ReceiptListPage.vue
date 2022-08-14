<template>
<div>
	<span class="h2">Receipt List</span>
	<v-card class="ma-3" v-for="receipt in receipts" dense>
		<v-card-title>{{receipt.name}}</v-card-title>
		<v-card-text>
			<v-row class="text-subtitle">{{readableDate(receipt.date)}}</v-row>
			<v-row>${{receipt.cost.toFixed(2)}}</v-row>
		</v-card-text>
		<v-card-actions>
			<v-btn :to="'/receipt/' + receipt.id" text> Expand </v-btn>
		</v-card-actions>
	</v-card>
</div>
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
		},
		readableDate(d) {
			const tempDate = new Date(d);
			const date = tempDate.getDate();

			let dateExt = 'th'
			if(date <= 10 || date > 20) {
				//get last number of date
				switch (parseInt(String(date).slice(-1))) {
					case 1:
						dateExt = 'st';
						break;
					case 2:
						dateExt = 'nd';
						break;
					case 3:
						dateExt = 'rd';
						break;
					default:
						break;
				}
			}
			return `${date}${dateExt} ${this.$store.state.months[tempDate.getMonth()]}`;
		}
	},
	computed: {
	}
}
</script>
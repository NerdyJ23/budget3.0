<template>
<div>
<v-card class="px-4">
	<v-card-title>
		<v-row>
			<v-col cols="2">
				Receipts
			</v-col>
			<v-col cols="10">
				<v-text-field
				v-model="search"
				append-icon="mdi-magnify"
				></v-text-field>
			</v-col>
		</v-row>
	</v-card-title>
	<v-card-text>
		<v-data-table
		dense
		:headers="headers"
		:search="search"
		:items="receipts"
		group-by="date"
	    show-expand

		>
		<template v-slot:item.cost="{item}">
			${{item.cost.toFixed(2)}}
		</template>

		<template v-slot:expanded-item="{ headers, item }">
			<td :colspan="headers.length">
				<v-row v-for="i in item.items" >
					<v-col cols="1">
						{{i.count}}x
					</v-col>
					<v-col cols="4">
						{{i.name}}
					</v-col>
					<v-col cols="4">
						{{i.category}}
					</v-col>
					<v-col cols="2">
						${{(i.count * i.cost).toFixed(2)}}
					</v-col>
				</v-row>
			</td>
		</template>
		</v-data-table>
	</v-card-text>
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
			headers: [{
				text: 'Name',
				value: 'name'
			},
			{
				text: 'Cost',
				value: 'cost'
			},
			{
				text: 'Date',
				value: 'date'
			},
			{
				text: 'Category',
				value: 'category'
			}
			],
			search: '',
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
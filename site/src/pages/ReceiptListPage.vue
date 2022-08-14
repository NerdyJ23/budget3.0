<template>
<div>
<v-card class="px-4">
	<v-card-title>
		<v-row>
			<v-col cols="1" class="d-flex align-center">
				<span>
					Receipts
				</span>
			</v-col>
			<v-col cols="11">
				<v-text-field
				v-model="search"
				append-icon="mdi-magnify"
				></v-text-field>
			</v-col>
		</v-row>
	</v-card-title>
	<v-card-text>
		<v-data-table
		:headers="headers"
		:search="search"
		:items="receipts"
		group-by="date"
	    :single-expand="true"
		show-expand
		:loading="!loaded"
		>
			<template v-slot:item.cost="{item}">
				${{item.cost.toFixed(2)}}
			</template>
			<template v-slot:item.actions="{item}">
			<div class="justify-end">
				<v-btn icon><v-icon>mdi-pencil</v-icon></v-btn>
				<v-btn icon><v-icon>mdi-delete</v-icon></v-btn>
			</div>
			</template>
			<template v-slot:expanded-item="{ headers, item }">
				<td :colspan="headers.length" class="py-4">
				<v-row class="font-weight-black text-uppercase pt-2" dense>
					<v-col cols="1">
					</v-col>
					<v-col cols="5">
						Name
					</v-col>
					<v-col cols="2">
						Cost
					</v-col>
					<v-col cols="2">
						Category
					</v-col>
				</v-row>
				<v-divider class="pb-2"></v-divider>
				<v-row v-for="a in item.items" :key="a.id" dense>
					<v-col cols="1">
					</v-col>
					<v-col cols="5">
						{{a.count}}x {{a.name}}
					</v-col>
					<v-col cols="2">
						${{(a.count).toFixed(2)}}
					</v-col>
					<v-col cols="2">
						{{a.category}}
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
			},
			{
				text: 'Actions',
				value: 'actions'
			}
			],
			search: '',
			apiUrl: this.$store.state.api,
			loaded: false
		}
	},
	methods: {
		async loadReceipts() {
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
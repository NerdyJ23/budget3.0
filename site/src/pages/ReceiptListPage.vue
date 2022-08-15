<template>
<div>
<v-card class="px-4">
	<v-card-title>
		<v-row>
			<v-col cols="1" class="d-flex align-center">
				<v-select
				:items="months"
				v-model="selectedMonth"
				@:change="loadReceipts()"
				label="Month"
				></v-select>
			</v-col>
			<v-col cols="1" class="d-flex align-center">
				<v-select
				:items="years"
				v-model="selectedYear"
				@:change="loadReceipts()"
				label="Year"
				></v-select>
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
				<v-btn icon @click.stop="editReceipt(item)"><v-icon color="blue lighten-2">mdi-pencil</v-icon></v-btn>
				<v-btn icon><v-icon color="red lighten-1">mdi-delete</v-icon></v-btn>
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
						${{(a.count * a.cost).toFixed(2)}}
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
<ReceiptDialog ref="receiptDialog"></ReceiptDialog>
</div>
</template>

<script>
import Receipt from '../components/Receipt';
import ReceiptDialog from '../components/Receipt/ReceiptDialog';
export default {
	components: {
		Receipt,
		ReceiptDialog
	},
	mounted() {
		this.init();
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
			loaded: false,
			years: [],
			selectedYear: 0,
			selectedMonth: 0,
			months: this.$store.state.months
		}
	},
	methods: {
		loadReceipts() {
			this.loaded = false;
			const today = new Date();
			const selectedMonth = this.months.indexOf(this.selectedMonth) > -1 ? this.months.indexOf(this.selectedMonth) : today.getMonth();

			fetch(`${this.apiUrl}/receipt?month=${selectedMonth}&year=${this.selectedYear}`, {
				method: 'GET',
			}).then(response => {
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {

				this.receipts = data.result;
				this.$store.commit('setToken',data.csrfToken);
				document.cookie="csrfToken=" + data.csrfToken;
				console.log(this.$store.state.csrfToken);
				for(let y in data.years) {
					this.years.push(data.years[y].date);
				}

				let thisYear = this.years.find((year) => {
					return year === today.getFullYear()
				});

				if(this.years.length === 0 || typeof thisYear === 'undefined') {
					this.years.push(today.getFullYear());
				}

				this.loaded = true;
			}).catch(err => {
				console.error(err);
				this.loaded = true;
			})
		},
		init() {
			const today = new Date();
			this.selectedMonth = 'June';//this.months[today.getMonth()];
			this.selectedYear = 2021;//today.getFullYear();
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
		},
		editReceipt(receipt) {
			this.$refs.receiptDialog.show();
			this.$refs.receiptDialog.receipt = {
				id: receipt.id,
				name: receipt.name,
				date: receipt.date
			};
			// console.log(receipt);
		}
	},
	watch: {
		// selectedMonth(old,current) {
		// 	this.loadReceipts();
		// },
		// selectedYear(old,current) {
		// 	this.loadReceipts();
		// }
	}
}
</script>
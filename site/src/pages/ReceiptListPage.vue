<template>
<div v-if="$store.state.validSession">
<v-card class="px-4">
	<v-card-title>
		<v-row>
			<v-col xl="1" lg="2" class="d-flex align-center">
				<v-select
				:items="GenericStore.months"
				v-model="selectedMonth"
				@:change="loadReceipts()"
				label="Month"
				></v-select>
			</v-col>
			<v-col xl="1" lg="1" class="d-flex align-center">
				<v-select
				:items="years"
				v-model="selectedYear"
				@:change="loadReceipts()"
				label="Year"
				></v-select>
			</v-col>
			<v-col xl="9" lg="8" class="d-none d-sm-flex">
				<v-text-field
				v-model="search"
				append-icon="mdi-magnify"
				></v-text-field>
			</v-col>
			<v-col cols="1" class="d-flex align-center">
				<v-btn outlined color="green lighten-1" @click="addReceipt">Add</v-btn>
			</v-col>
		</v-row>
	</v-card-title>
	<v-card-text>
	width: {{$vuetify.breakpoint.width}}
		<v-data-table
		:headers="headers"
		:search="search"
		:items="receipts"
		:calculateWidths="true"
		group-by="date"
		dense
		mobileBreakpoint="750"
		:loading="!loaded"
		>
			<template v-slot:group.header="{item, group, headers}" style="line-height: 1 !important">
			<td :colspan="headers.length" class="primary" >
				<v-col cols="12" class="d-flex">
					<span class="receipt align-self-center">{{group}}</span>
				</v-col>
			</td>
			</template>

			<template v-slot:item="{item}">
				<tr @click="viewReceipt(item)" class="bg">
					<td>
						<span>{{item.location}}</span>
					</td>
					<td>
						<span>{{item.name}}</span>
					</td>
					<td>
						${{item.cost.toFixed(2)}}
					</td>
					<td>
						{{item.category}}
					</td>
					<td class="justify-end d-flex">
						<div>
							<v-btn icon @click.stop="editReceipt(item)"><v-icon color="blue lighten-2">mdi-pencil</v-icon></v-btn>
							<v-btn icon @click.stop="deleteReceipt(item)"><v-icon color="red lighten-1">mdi-delete</v-icon></v-btn>
						</div>
					</td>
				</tr>
			</template>
		</v-data-table>
	</v-card-text>
</v-card>
<ReceiptDialog ref="receiptDialog" @updated="loadReceipts"></ReceiptDialog>
</div>
<AccessDeniedPage v-else></AccessDeniedPage>
</template>

<script>
import Receipt from '../components/Receipt';
import ReceiptDialog from '../components/Receipt/ReceiptDialog';
import AccessDeniedPage from './ErrorPages/AccessDeniedPage.vue';
import { mapState } from "vuex";
import cakeApi from "../services/cakeApi";;

export default {
	components: {
    Receipt,
    ReceiptDialog,
    AccessDeniedPage
},
	name: "ReceiptListPage",
	mounted() {
		this.init();
		this.loadReceipts();
	},
	data: function() {
		return {
			receipts: [],
			headers: [
			{
				text: 'Store',
				value: 'name'
			},
			{
				text: 'Location',
				value: 'location'
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
			loaded: false,
			years: [],
			selectedYear: 0,
			selectedMonth: 0,
		}
	},
	methods: {
		async loadReceipts() {
			this.loaded = false;
			const today = new Date();
			const selectedMonth = this.GenericStore.months.indexOf(this.selectedMonth) > -1 ? this.GenericStore.months.indexOf(this.selectedMonth) : today.getMonth();
			const response = await cakeApi.listReceipts(selectedMonth+1, this.selectedYear);
			if (response.status >= 300) {
				console.error(response.statusText);
			} else {
				const data = response.data;
				this.receipts = data.result;
				for(let y in data.years) {
					this.years.push(data.years[y].date);
				}

				let thisYear = this.years.find((year) => {
					return year === today.getFullYear()
				});

				if(this.years.length === 0 || typeof thisYear === 'undefined') {
					this.years.push(today.getFullYear());
				}
			}
			this.loaded = true;
		},
		init() {
			const today = new Date();
			this.selectedMonth = this.GenericStore.months[today.getMonth()];
			this.selectedYear = today.getFullYear();
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
			return `${date}${dateExt} ${this.GenericStore.months[tempDate.getMonth()]}`;
		},
		editReceipt(receipt) {
			this.$refs.receiptDialog.setMode('Edit');
			this.$refs.receiptDialog.show();

			this.$refs.receiptDialog.setReceipt(JSON.stringify(receipt));
		},
		addReceipt() {
			this.$refs.receiptDialog.setMode('Add');
			this.$refs.receiptDialog.show();
		},
		viewReceipt(receipt) {
			this.$refs.receiptDialog.setMode('View');
			this.$refs.receiptDialog.show();

			this.$refs.receiptDialog.setReceipt(JSON.stringify(receipt));
		},
		async deleteReceipt(receipt) {
			const response = await cakeApi.deleteReceipt(receipt.id);

			if (response.status <= 300) {
				console.log("noice");
				this.loadReceipts();
			} else {
				console.error("uh oh");
			}
		}
	},
	watch: {
		selectedMonth(old,current) {
			if(this.loaded) {
				this.loadReceipts();
			}
		},
		selectedYear(old,current) {
			if(this.loaded) {
				this.loadReceipts();
			}
		}
	},
	computed: {
		...mapState(["GenericStore"]),

		validSession() {
			return this.$store.getters.checkValidSession;
		},

	}
}
</script>
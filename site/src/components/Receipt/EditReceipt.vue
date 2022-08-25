<template>
	<div v-if="visible">
		<v-card class="pa-4">
			<v-card-title class="text-center justify-space-around receipt">{{mode}} Receipt</v-card-title>
			<v-card-text>
				<v-form>
					<v-row>
						<v-col cols="8">
							<v-text-field
								v-model="receipt.name"
								label="Name/Location"
							></v-text-field>
						</v-col>
						<v-col cols="4">
							<v-text-field
							v-model="receipt.date"
							prepend-icon="mdi-calendar"
							@click="datePick = true"
							label="Date"
							readonly
							></v-text-field>
						</v-col>
					</v-row>
					<!-- Header Row -->
					<v-row>
						<v-col>
							<span>Item Name</span>
						</v-col>
						<v-col>
							<span>Count</span>
						</v-col>
						<v-col>
							<span>Price</span>
						</v-col>
						<v-col>
							<span>Line Total</span>
						</v-col>
					</v-row>
					<v-divider></v-divider>
					<br />
					<!-- Item Rows Here -->
					<v-row v-for="(item, index) in receipt.items">
						<v-col cols="5">
							<v-text-field
								:label="index === 0 ? 'Item Name' : ''"
								v-model="item.name"
								dense
							></v-text-field>
						</v-col>
						<v-col cols="1">
							<v-text-field
								:label="index === 0 ? 'Count' : ''"
								v-model="item.count"
								:rules="rules.numberRules"
								type="number"
								dense
							></v-text-field>
						</v-col>
						<v-col cols="1">
							<v-text-field
								:label="index === 0 ? 'Price (per item)' : ''"
								v-model="item.price"
								type="number"
								step=".01"
								prefix="$"
								@blur="fixPrice(item, index)"
								hide-spin-buttons
								dense

							></v-text-field>
						</v-col>
						<v-col cols="1">
							<v-text-field disabled
									prefix="$"
									label="Total"
									v-model="(item.price * item.total).toFixed(2)"
									dense
							></v-text-field>
						</v-col>
						<v-col cols="2">
							<v-text-field
							label="Category"
							v-model="item.category"
							dense
							>

							</v-text-field>
						</v-col>
						<v-col cols="1">
							<v-btn color="red" outlined @click="removeItem(index)">Remove</v-btn>
						</v-col>
					</v-row>
					<v-row >
						<v-col cols="11">
							<v-divider></v-divider>
						</v-col>
						<v-col cols="1" class="d-flex">
							<v-btn
							class="align-self-baseline"
							@click="newItem"
							icon
							color="blue"
							>
								<v-icon>mdi-plus-circle</v-icon>
							</v-btn>
						</v-col>
					</v-row>
				</v-form>
			</v-card-text>
			<v-card-actions class="d-flex justify-end">
				<v-btn @click="$emit('save')" color="green lighten-2">Save</v-btn>
				<v-btn color="red" @click="$emit('close')" outlined>Cancel</v-btn>
			</v-card-actions>
		</v-card>

		<v-dialog v-model="datePick" width="50vw">
			<v-date-picker v-model="receipt.date" full-width>
				<template v-slot="{item}">
					<v-row>
						<v-col cols="12" class="d-flex align-end">
							<v-btn
							class="px-2 mx-2 flex-grow-1"
							@click.stop="datePick = false"
							color="green"
							outlined
							>Save</v-btn>
						</v-col>
					</v-row>

				</template>
			</v-date-picker>
		</v-dialog>
	</div>
</template>

<script>
export default {
	props: {
		mode: {
			type: String,
			required: true
		}
	},
	data: function() {
		return {
			datePick: false,
			receipt: {
				name: '',
				items: []
			},
			defaults: {
				item: {
					name: '',
					count: 1,
					price: 0,
					category: '',
					total: 0
				}
			},
			visible: false,
			rules: {
				numberRules: [
					v => !!v || 'Please enter a number',
					v => !isNaN(v) || `Please only enter integers`
				]
			}
		}
	},
	mounted() {
		this.init();
	},

	inject: ['getReceipt'],
	methods: {
		init() {
			console.log(`there are ${this.receipt.items.length} items attached to this receipt`);
			if(typeof this.receipt.items === 'undefined') {
				this.receipt.items = [];
			}
			// if(this.receipt.items.length === 0) {
			// 	this.newItem();
			// }
			this.show();
		},
		show() {
			this.visible = true;
			this.receipt = this.getReceipt();
			console.log('receipt is');
			console.log(this.receipt);
		},
		newItem() {
			var newItem = {};
			Object.entries(this.defaults.item).forEach(entry => {
				const [key, value] = entry;
				newItem[key] = value;
			})
			this.receipt.items.push(newItem);
		},
		removeItem(item) {
			console.log(item);
			this.receipt.items.splice(item,1);
		},
		validateItem(item, key) {
			console.log(item.price);
			item.price = parseInt(item.price).toFixed(2);
			item.total = (parseInt(item.count) * parseInt(item.price)).toFixed(2);
		},
		fixPrice(item, index) {
			console.log(item);
			item.price = parseFloat(item.price).toFixed(2);
			this.refresh();
		},
		refresh() {
			this.receipt.items.push({});
			this.receipt.items.pop();
		}
	},
	computed: {
		receiptLoaded() {
			return typeof this.receipt.name !== 'undefined';
		},
	}
}
</script>
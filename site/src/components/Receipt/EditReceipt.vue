<template>
	<div v-if="visible">
		<v-card>
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
								label="Item Name"
								v-model="item.name"
							></v-text-field>
							{{item}}
						</v-col>
						<v-col cols="1">
							<v-text-field
								label="Count"
								v-model="item.count"
								:rules="rules.numberRules"
								@change="validateItem(item)"
								type="number"
							></v-text-field>
						</v-col>
						<v-col cols="2">
							<v-text-field
								label="Price (per item)"
								v-model="item.price"
								@change="validateItem(item,key)"
								type="currency"
								prefix="$"
							></v-text-field>
						</v-col>
						<v-col cols="2">
							<v-text-field disabled
									prefix="$"
									label="Total"
									v-model="item.total"
							></v-text-field>
						</v-col>
						<v-col cols="2">
							<v-btn color="red" outlined @click="removeItem(index)">Remove</v-btn>
						</v-col>
					</v-row>
					<v-row>
						<v-btn
						@click="newItem"
						>Add Line Item</v-btn>
					</v-row>
				</v-form>
			</v-card-text>
			<v-card-actions>
				<v-btn @click="$emit('save')">Save</v-btn>
				<v-btn color="red" @click="$emit('close')">Cancel</v-btn>
			</v-card-actions>
		</v-card>

		<v-dialog v-model="datePick" width="50vw">
			<v-date-picker v-model="receipt.date" full-width>
				<template v-slot="{item}">
					<v-row>
						<v-col cols="12" class="d-flex align-end">
							<v-spacer></v-spacer>
							<v-btn class="px-2 mx-2" @click.stop="datePick = false">Save</v-btn>
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
		}
	},
	computed: {
		receiptLoaded() {
			console.log(`receipt`);
			console.log(this.receipt);
			console.log(`receipt type: ${typeof this.receipt}`);
			console.log(`loaded? ${typeof this.receipt !== 'boolean'}`)
			return typeof this.receipt.name !== 'undefined';
		},
	}
}
</script>
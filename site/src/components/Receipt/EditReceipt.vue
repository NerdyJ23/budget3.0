<template>
	<div v-if="visible">
		<v-card class="pa-4">
			<v-card-title class="justify-space-around">
				<span class="receipt primary--text">{{mode}} Receipt</span>
			</v-card-title>
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
							class="primary--icon"
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
					<v-row v-for="(item, index) in receipt.items" :key="item.id">
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
								v-model="item.cost"
								type="number"
								step=".01"
								prefix="$"
								@change="fixPrice(item, index)"
								hide-spin-buttons
								dense

							></v-text-field>
						</v-col>
						<v-col cols="1">
							<v-text-field disabled
									prefix="$"
									label="Total"
									dense
									:value="(item.cost * item.count).toFixed(2)"
							></v-text-field>
						</v-col>
						<v-col cols="2">
							<v-text-field
							label="Category"
							v-model="item.category"
							@input="item.category = item.category.toUpperCase()"
							dense
							>

							</v-text-field>
						</v-col>
						<v-col cols="1">
							<v-btn color="error" outlined @click="removeItem(index)">Remove</v-btn>
						</v-col>
					</v-row>
					<v-row >
						<v-col cols="5" class="d-flex">
							<v-divider class="align-self-center"></v-divider>
						</v-col>
						<v-col cols="2" class="d-flex justify-space-around">
							<v-btn
								class="align-self-centertext-center"
								@click="newItem"
								outlined

							>
								Add new item
							</v-btn>
						</v-col>
						<v-col cols="5" class="d-flex">
							<v-divider class="align-self-center"></v-divider>
						</v-col>
					</v-row>
				</v-form>
			</v-card-text>
			<v-card-actions class="d-flex justify-end">
				<v-btn @click="$emit('save')" color="primary">Save</v-btn>
				<v-btn color="error" @click="$emit('close')" outlined>Cancel</v-btn>
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
			delete: [],
			defaults: {
				item: {
					id: 0,
					name: '',
					count: 1,
					cost: 0,
					category: ''
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
		console.log('Edit Receipt mounted');
	},

	inject: ['getReceipt'],
	methods: {
		init() {
			this.setDefaults();
			this.show();
		},
		show() {
			this.visible = true;
			this.receipt = JSON.parse(this.getReceipt());
			console.log(`After show name is ${this.receipt.name}`);
		},
		newItem() {
			var newItem = {};
			Object.entries(this.defaults.item).forEach(entry => {
				const [key, value] = entry;
				newItem[key] = value;
			});
			this.receipt.items.push(newItem);
		},
		removeItem(item) {
			console.log(item);
			if(item.id !== 0) {
				this.delete.push(this.receipt.items[item]);
			}
			this.receipt.items.splice(item,1);
		},
		fixPrice(item, index) {
			//fix for floating point number not rounding up on 5
			const lastChar = item.cost.slice(-1);
			if(lastChar === '5') { // 0.5 is really 0.49999999999~
				item.cost = parseFloat(item.cost) + .001;
			}
			this.receipt.items[index].cost = parseFloat(item.cost).toFixed(2);
		},
		refresh() {
			this.receipt.items.push({});
			this.receipt.items.pop();
		},
		setDefaults() {
			console.log('Defaults being set');
			this.receipt = {
				name: '',
				items: []
			};
			console.log(`Receipt default name is ${this.receipt.name}`);
			this.visible = false;
			this.datePick = false;
		}
	},
	computed: {
		receiptLoaded() {
			return typeof this.receipt.name !== 'undefined';
		},
	},
	// watch: {
	// 	visible: function (oldVal, newVal) {
	// 		this.$nextTick(() => {
	// 			console.log('visible changed');
	// 			this.init();
	// 		});
	// 	}
	// }
}
</script>

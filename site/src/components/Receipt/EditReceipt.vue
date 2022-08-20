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
							></v-text-field>
						</v-col>
						<v-col cols="4">
							<v-text-field
							v-model="receipt.date"
							prepend-icon="mdi-calendar"
							@click="datePick = true"
							readonly
							></v-text-field>
						</v-col>
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
			receipt: false,
			visible: false,
		}
	},
	mounted() {
		this.init();
	},

	inject: ['getReceipt'],
	methods: {
		init() {
			this.show();
		},
		show() {
			this.visible = true;
			this.receipt = this.getReceipt();
			console.log('receipt is');
			console.log(this.receipt);
		}
	},
	computed: {
		receiptLoaded() {
			console.log(`receipt`);
			console.log(this.receipt);
			console.log(`receipt type: ${typeof this.receipt}`);
			console.log(`loaded? ${typeof this.receipt !== 'boolean'}`)
			return typeof this.receipt !== 'boolean';
		}
	}
}
</script>
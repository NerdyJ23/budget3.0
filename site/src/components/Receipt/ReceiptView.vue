<template>
	<v-card class="pa-4">
		<v-card-title class="text-center justify-space-around receipt primary--text">{{title}}</v-card-title>
		<v-card-text class="receipt-item">
			<div>
			
			</div>
			<div>
			<v-row>
				<v-col cols="12" class="text-center">
					************************************************************
				</v-col>
			</v-row>
			<v-row v-for="item in receipt.items" class="px-10">
				<v-col cols="6">
					<span v-if="isFloat(item.count)">
						{{item.name}} - {{item.count}}kg
					</span>
					<span v-else-if="item.count > 1">
						{{item.count}}x {{item.name}}
					</span>
					<span v-else>
						{{item.name}}
					</span>
				</v-col>
				<v-col cols="2">
					${{(item.cost * item.count).toFixed(2)}}
				</v-col>
				<v-col cols="4" class="text-center">
					{{item.category}}
				</v-col>
			</v-row>
			<v-row>
				<v-divider class="pb-2"/>
			</v-row>
			<v-row v-if="receipt.items.length <= 0">
				<v-col cols="12" class="text-center">
					<span class="justify-space-around text-h5">Empty...</span>
				</v-col>
			</v-row>
			<v-row v-else style="font-size: 1.5em">
				<v-col cols="8"></v-col>
				<v-col cols="2" class="text-end">Total:</v-col>
				<v-col cols="1">${{receipt.cost.toFixed(2)}}</v-col>
				<v-col cols="1"></v-col>
			</v-row>
		</div>
		</v-card-text>
		<v-card class="d-flex justify-end sticky-bar" elevation="0">
			<v-card-actions>
				<v-btn color="blue lighten-2">Edit</v-btn>
				<v-btn outline color="red">Close</v-btn>
			</v-card-actions>
		</v-card>
	</v-card>
</template>

<script>
export default {
	name: "ReceiptView",
	props: {
		receipt: {
			type: Object,
			required: true
		}
	},
	mounted() {
		console.log("moutned view");
		console.log(this.receipt);
	},
	methods: {
		isFloat(value) {
			return (value.toString()).indexOf('.') !== -1;
		}
	},
	computed: {
		title() {
			if (this.receipt.receiptNumber !== null) {
				return `${this.receipt.receiptNumber} - ${this.receipt.name}`;
			}
			return this.receipt.name;
		}
	}
}
</script>


<template>
	<v-card>
		<v-card-text>{{receipt.name}}</v-card-text>
	</v-card>
</template>

<script>
export default {
	props: {
		receipt: {
			type: Object,
			required: true
		}
	},
	mounted() {
		console.log("moutned view");
		console.log(this.receipt);
	}
}
</script>
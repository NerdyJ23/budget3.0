<template>
	<v-container :fluid="true">
		<!-- Tiny phone screens go here, use inline dialog-->
		<div v-for="(item, index) in receipt.items" :key="item.id" class="item-row">
			<v-row dense>
				<v-col cols="5">
					<v-icon small color="blue">mdi-pencil</v-icon>
					{{item.name}}
				</v-col>
				<v-col cols="3">
					${{(item.cost * item.count).toFixed(2)}}
				</v-col>
				<v-col cols="4">
					<v-chip outlined small color="primary">
						{{item.category}}
					</v-chip>
				</v-col>
			</v-row>

		</div>
	</v-container>
</template>

<script>
export default {
	props: {
		receipt: {
			type: Object,
			required: true
		},
		mode: {
			type: String,
			required: true
		}
	}
}
</script>
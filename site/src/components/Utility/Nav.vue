<template>
	<v-toolbar elevation="6">
		<v-toolbar-title class="text-h5">
			<v-btn to="/" plain>Home</v-btn>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<v-btn to="/receipt" plain>My Receipts</v-btn>
		<v-btn to="/graph" plain>Overview</v-btn>
	</v-toolbar>
</template>

<script>

export default {
	props: {
		page: {
			required: false,
			type: String
		}
	}
}
</script>

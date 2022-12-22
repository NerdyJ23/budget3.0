<template>
	<v-row>
		<v-col cols="2">
			<v-icon :color="`${darkTheme ? '' : 'primary'}`">mdi-white-balance-sunny</v-icon>
		</v-col>
		<v-col cols="3">
			<v-switch v-model="darkTheme"></v-switch>
		</v-col>
		<v-col cols="1">
			<v-icon :color="`${darkTheme ? 'primary' : ''}`">mdi-moon-waning-crescent</v-icon>
		</v-col>
	</v-row>
</template>
<script>
export default {
	name: "ThemeToggle",
	data() {
		return {
			darkTheme: false
		}
	},
	watch: {
		darkTheme() {
			this.$vuetify.theme.dark = this.darkTheme;
		}
	}
}
</script>
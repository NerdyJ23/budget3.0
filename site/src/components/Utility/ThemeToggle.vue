<template>
	<div class="d-flex d-flex--row">
		<v-icon class="pr-2" :color="`${darkTheme ? '' : 'primary'}`">mdi-white-balance-sunny</v-icon>
		<v-switch v-model="darkTheme"></v-switch>
		<v-icon class="pl-1" :color="`${darkTheme ? 'primary' : ''}`">mdi-moon-waning-crescent</v-icon>
	</div>
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
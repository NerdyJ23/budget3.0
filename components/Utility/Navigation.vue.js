Vue.component('navigation', {
template: `
<v-card flat tile>
	<v-toolbar dense>
		<v-toolbar-title>Budgeting: Now with Vue!</v-toolbar-title>
		<v-spacer></v-spacer>
		<v-btn text @click="login">Login</v-btn>
	</v-toolbar>
</v-card>
`
,methods: {
	login() {
		window.location.href ='/login.php';
	}
}
});
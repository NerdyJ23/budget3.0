<template>
<div>
	<v-toolbar elevation="6">
		<v-toolbar-title class="text-h5">
			<v-btn to="/" plain>Home</v-btn>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<v-btn to="/receipt" plain>My Receipts</v-btn>
		<v-btn to="/graph" plain>Overview</v-btn>
		<v-btn @click="showLogin" plain>Login</v-btn>
	</v-toolbar>
	<Login ref="login"></Login>
</div>
</template>

<script>

import Login from '../Login/LoginDialog';
export default {
	props: {
		page: {
			required: false,
			type: String
		}
	},
	data: function () {
		return {
			loginVisible: true
		}
	},
	components: {
		Login
	},
	methods: {
		showLogin() {
			this.$refs.login.show();
		}
	}
}
</script>

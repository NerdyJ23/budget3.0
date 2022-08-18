<template>
<div>
	<v-toolbar elevation="6">
		<v-toolbar-title class="text-h5">
			<v-btn to="/" plain>Home</v-btn>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<div v-if="validSession">
			<v-btn to="/receipt" plain>My Receipts</v-btn>
			<v-btn to="/graph" plain>Overview</v-btn>
		</div>
		<div v-else>
			<v-btn @click="showLogin" plain>Login</v-btn>
		</div>
	</v-toolbar>
	<Login ref="login" @loggedin="validSession = true"></Login>
</div>
</template>

<script>
import Login from '../Login/LoginDialog';
import Cookies from 'js-cookie';

export default {
	props: {
		page: {
			required: false,
			type: String
		}
	},
	data: function () {
		return {
			validSession: false
		}
	},
	components: {
		Login
	},
	methods: {
		init() {
			if(Cookies.get('token') !== 'undefined') {
				this.validSession = true;
			}
		},
		showLogin() {
			this.$refs.login.show();
		}
	}
}
</script>

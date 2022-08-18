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
			<v-btn plain @click="logout">Logout</v-btn>
		</div>
		<div v-else>
			<v-btn @click="showLogin" @loggedin="validSession = setValidSession" plain>Login</v-btn>
		</div>
	</v-toolbar>
	<Login ref="login"></Login>
</div>
</template>

<script>
import Login from '../Login/LoginDialog';
import Cookies from 'js-cookie';

export default {
	mounted() {
		this.init();
		this.validSession = this.setValidSession();
	},
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
		},
		showLogin() {
			this.$refs.login.show();
		},
		logout() {
			Cookies.remove('token', {path:'/'});
			window.location = '/';
		},
		setValidSession() {
			// return true;
			console.log(`valid session? : ${this.$store.getters.checkValidSession}`);
			return this.$store.getters.checkValidSession;
		}
	}
}
</script>

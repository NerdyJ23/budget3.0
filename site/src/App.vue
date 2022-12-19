<template>
  <v-app>
    <v-main>
		<Navbar @toggleDrawer="toggleDrawer" ref="navbar" @login="showLogin" @logout="logout" />
		<v-divider></v-divider>
		<v-card class="d-flex" elevation="0">
			<NavigationDrawer class="col-2" ref="drawer" :style="`min-height: ${contentHeight}px`" @login="showLogin" @logout="logout"></NavigationDrawer>
			<v-divider vertical />
			<router-view style="width:auto" class="pl-10 col-10"></router-view>
		</v-card>
		<Login ref="login"/>
    </v-main>
  </v-app>
</template>

<script>
import Navbar from './components/Utility/Nav';
import NavigationDrawer from './components/Utility/NavigationDrawer';
import { mapState } from "vuex";
import Login from './components/Login/LoginDialog';

export default {
	mounted() {
		const self = this;
		this.calcHeight();
		window.addEventListener('resize', () => {
			self.calcHeight();
		})
		// this.$vuetify.theme.dark = true;
	},
	data() {
		return {
			contentHeight: 0
		}
	},
  	name: 'App',
  	components: {
		Navbar,
		NavigationDrawer,
		Login
  	},
	methods: {
		toggleDrawer() {
			this.$refs.drawer.toggle();
		},
		calcHeight() {
			const self = this;
			this.$nextTick(() => {
				self.contentHeight = window.innerHeight - self.$refs.navbar.$el.clientHeight - 4;
			});
		},
		showLogin() {
			this.$refs.login.show();
		},
		logout(){
			this.$store.dispatch('logout');
		}
	},
	computed: {
		...mapState(["GenericStore"])
	}
}
</script>

<style>
@font-face {
	font-family: "Ticket";
	src: local("Ticket"),
	url(./assets/fonts/Ticketing.ttf) format("truetype");
}

@font-face {
	font-family: "Coder-Font";
	src: local("Coder-Font"),
	url(./assets/fonts/Semi-Coder-Regular.otf) format("opentype");
}
.receipt {
	font-family: "Ticket";
	font-size: 2em;
}

.receipt-item {
	font-family: "Ticket";
	font-size: 1.25em;
}
</style>
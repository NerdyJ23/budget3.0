<template>
  <v-app>
    <v-main>
		<Navbar @toggleDrawer="toggleDrawer"></Navbar>
		<v-divider></v-divider>
		<v-card class="d-flex flex-grow-1">
			<NavigationDrawer class="pl-4 col-2" ref="drawer"></NavigationDrawer>
			<router-view style="width:auto" class="pl-10 col-10"></router-view>
		</v-card>
    </v-main>
  </v-app>
</template>

<script>
import Navbar from './components/Utility/Nav';
import NavigationDrawer from './components/Utility/NavigationDrawer';
import { mapState } from "vuex";

export default {
	mounted() {
		// this.$vuetify.theme.dark = true;
	},
  	name: 'App',
  	components: {
		Navbar,
		NavigationDrawer
  	},
	methods: {
		toggleDrawer() {
			this.$refs.drawer.toggle();
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
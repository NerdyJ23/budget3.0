<template>
  <v-app>
    <v-main>
		<Navbar @toggleDrawer="toggleDrawer" class="pb-2"></Navbar>
		<v-divider></v-divider>
		<v-card class="fill-height rounded-0">
			<v-row class="fill-height">
				<NavigationDrawer v-show="GenericStore.drawerExpanded" class="pl-4" :class="[{'col-2': GenericStore.drawerExpanded}]" ref="drawer"></NavigationDrawer>
				<router-view style="width:auto" class="pl-10" :class="`${GenericStore.drawerExpanded ? 'col-10' : 'col-12'}`"></router-view>
			</v-row>
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
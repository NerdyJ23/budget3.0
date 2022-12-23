<template>
<div>
	<v-toolbar elevation="1">
		<!-- <v-btn plain @click="$emit('toggleDrawer')"><v-icon>mdi-menu</v-icon></v-btn> -->
		<v-toolbar-title class="text-h5">
			<v-btn to="/" plain>Budgeting and stuff ❤</v-btn>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<div>
			<template v-if="GenericStore.validSession">
				<v-btn to="/receipt" plain>My Receipts</v-btn>
				<v-btn to="/graph" plain>Overview</v-btn>
				<v-btn plain @click="$emit('logout')">Logout</v-btn>
			</template>
			<template v-else>
				<v-btn @click="$emit('login')" plain>Login</v-btn>
			</template>
		</div>

	</v-toolbar>
</div>
</template>

<script>
import { mapState } from "vuex";
import ThemeToggle from "./ThemeToggle";

export default {
	name: "Navbar",
	computed: {
		...mapState(["GenericStore"])
	},
	components: {
		ThemeToggle
	}
}
</script>

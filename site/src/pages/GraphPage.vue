<template>
	<div v-if="$store.state.validSession">
		<Graph v-if="loaded" :dataPoints=dataPoints></Graph>
	</div>
	<AccessDeniedPage v-else></AccessDeniedPage>
</template>

<script>
import Graph from '../components/Graph/Graph.vue';
import AccessDeniedPage from './ErrorPages/AccessDeniedPage.vue';
import cakeApi from "../services/cakeApi";;

export default {
	components: {
    Graph,
    AccessDeniedPage
},
	mounted() {
		this.getSession();
	},
	data: function() {
		return {
			loaded: false,
			dataPoints: null,
			apiUrl: this.$store.state.api
		}
	},
	methods: {
		async getSession() {
			const response = await cakeApi.listReceipts(8, 2022);
			if (response.status >= 300) {
				console.error(response.statusText);
			} else {
				this.dataPoints = response.data.result;
			}
			this.loaded = true;
		}
	},
	computed: {
		validSession() {
			return this.$store.getters.checkValidSession;
		}
	}

}
</script>
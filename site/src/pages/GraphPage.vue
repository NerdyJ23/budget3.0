<template>
	<div v-if="validSession">
		<Graph v-if="loaded" :dataPoints=dataPoints></Graph>
	</div>
	<AccessDeniedPage v-else></AccessDeniedPage>
</template>

<script>
import Graph from '../components/Graph/Graph.vue';
import AccessDeniedPage from './ErrorPages/AccessDeniedPage.vue';

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
		getSession() {
			fetch(`${this.apiUrl}/receipt?month=5&year=2021`, {
				method: 'GET',
				credentials: 'include'
			}).then(response => {
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				console.log(data);
				this.dataPoints = data.result;
				this.loaded = true;
			}).catch(err => {
				console.error(err);
				this.loaded = true;
			})
		}
	},
	computed: {
		validSession() {
			return this.$store.getters.checkValidSession;
		}
	}

}
</script>
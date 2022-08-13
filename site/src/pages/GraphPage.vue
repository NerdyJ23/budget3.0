<template>
<div>
	<Graph v-if="loaded" :dataPoints=dataPoints></Graph>
</div>
</template>

<script>
import Graph from '../components/Graph/Graph.vue';

export default {
	components: {
		Graph
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
	}

}
</script>
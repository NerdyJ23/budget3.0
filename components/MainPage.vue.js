Vue.component('main-page', {
	template: `
	<div>
		<navigation></navigation>
		<budget-graph v-if="loaded" :dataPoints="dataPoints"></budget-graph>
	</div>
	`
	,mounted() {
		this.getSession();
		this.getChartJS();
	}
	,data: function() {
		return {
			loaded: false,
			dataPoints: null,
		}
	}
	,methods: {
		getSession() {
			fetch("/AJAX/BUDGET_GET_GRAPH.php", {
				method: 'POST',
				headers: {
					'content-type': 'application/json'
				},
				body: JSON.stringify({month: 7, year: 2021})
			}).then(response => {
				console.log(response);
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				this.dataPoints = data;
				this.loaded = true;
			}).catch(err => {
				console.error(err);
				this.loaded = true;
			})
		}
		,getChartJS() {
			let chartJS = document.createElement('script')
			chartJS.setAttribute('src', 'https://cdn.jsdelivr.net/npm/chart.js')
			document.head.appendChild(chartJS);
		}
	}
	,provide: function() {
		return {
			getChartJS: this.getChartJS
		}
	}
})
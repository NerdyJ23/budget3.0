Vue.component('main-page', {
	template: `
	<div>
		<navigation></navigation>
		<budget-graph v-if="loaded" :dataPoints="dataPoints"></budget-graph>
	</div>
	`
	,mounted() {
		this.getSession();
	}
	,data: function() {
		return {
			loaded: false,
			dataPoints: null,
		}
	}
	,methods: {
		getSession() {
			fetch("/AJAX/getReceiptItems.php", {
				method: 'POST',
				headers: {
					'content-type': 'application/json'
				},
				body: JSON.stringify({
					month: 7
					,year: 2021
					,type: 'total'
				})
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
	}
})
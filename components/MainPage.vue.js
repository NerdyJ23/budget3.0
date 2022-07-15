Vue.component('main-page', {
template: `
<div @click="getSession()">
	<navigation ></navigation>
</div>
`
,data: function() {
	return {
		none: null
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
			console.log(data);
			console.log(data.sql);
		}).catch(err => {
			console.error(err);
		})
	}
}
})
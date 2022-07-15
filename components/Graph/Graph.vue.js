Vue.component('budget-graph', {
	template: `
		{{monthStruct[month]}}
	`
	,data: function () {
		return {
			monthStruct: ['January','February','March','April','May','June','July','August','September','October','November','December'],
			month: 0,
			year: 0000
		}
	}
})
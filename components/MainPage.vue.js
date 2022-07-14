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
		
	}
}
})
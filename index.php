<html>
	<head>
		<?php include('templates/header.php') ?>
		<title>Budgeting</title>
		<script src="/components/MainPage.vue.js"></script>
		<script src="/components/Utility/Mavigation.vue.js"></script>

	</head>
	<script type="text/javascript">
		$(function(){
			var budgetingApp = new Vue({
			el: '#app'
			,vuetify: new Vuetify()
			});
		});
	</script>

	<v-app id="app">
		<main-page></main-page>
	</v-app>
</html>
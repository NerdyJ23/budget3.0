import Cookies from 'js-cookie'

const state = {
	api: 'https://budget.jessprogramming.com:8080',
	// site: 'https://budget.jessprogramming.com',
	// api: 'http://localhost:5555',
	site: 'http://localhost',
	months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
	weekdays: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
	validSession: false,
	drawerExpanded: false,
  }

const getters = {
	checkValidSession() {
		console.log(`cookie: ${Cookies.get('token')}`);
		console.log(`all cookies: ${document.cookie}`);
		if(typeof Cookies.get('token') === 'undefined') {
			return false;
		}
		return true;
	}
  }

  export default {
	state,
	getters
}
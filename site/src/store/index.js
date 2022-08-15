import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
	api: 'https://budget.jessprogramming.com:8080',
	site: 'https://budget.jessprogramming.com',
	months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
	weekdays: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
  },
  getters: {
  },
  mutations: {
	setToken(state, x) {
		state.csrfToken = x;
	}
  },
  actions: {
  },
  modules: {
  }
})

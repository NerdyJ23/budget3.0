import Vue from 'vue'
import Vuetify from 'vuetify/lib';
import colors from 'vuetify/lib/util/colors';

import VueRouter from 'vue-router';

import App from './App.vue'

import Home from './pages/Home';
import NotFoundPage from './pages/ErrorPages/NotFoundPage';
import GraphPage from './pages/GraphPage';
import ReceiptListPage from './pages/ReceiptListPage';
import ReceiptPage from './pages/ReceiptPage';
import vuetify from './plugins/vuetify'
import store from './store'
// import '@babel/polyfill'

Vue.config.productionTip = false;
Vue.use(VueRouter);

const routes = [
	{path: '/', component: Home},
	{path: '/graph', component: GraphPage},
	{path: '/receipt', component: ReceiptListPage},
	// {path: '/receipt/:receiptid', component: ReceiptPage},

	{path: '*', component: NotFoundPage},
];

const router = new VueRouter({
	mode: 'history',
	routes,
})

new Vue({
    render: h => h(App),
    vuetify,
    store,
    router
}).$mount('#app')

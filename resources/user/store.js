import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'

const store = new Vuex.Store({
	state: {
		auth: {
			id: '',
			store_id: '',
			name: '',
			email: '',
			avatar: ''
		}
	},
	mutations: {
		setAuthUserDetail (state, auth) {
        	for (let key of Object.keys(auth)) {
                state.auth[key] = auth[key];
            }
            if ('avatar' in auth)
            	state.auth.avatar = auth.avatar !== null ? auth.avatar : 'avatar.png';
		},
		resetAuthUserDetail (state) {
        	for (let key of Object.keys(state.auth)) {
                state.auth[key] = '';
            }
		},
		setConfig (state, config) {
        	for (let key of Object.keys(config)) {
                state.config[key] = config[key];
            }
		}
	},
	actions: {
		setAuthUserDetail ({ commit }, auth) {
     		commit('setAuthUserDetail',auth);
     	},
     	resetAuthUserDetail ({commit}){
     		commit('resetAuthUserDetail');
     	},
		setConfig ({ commit }, data) {
     		commit('setConfig',data);
     	}
	},
	getters: {
		getAuthUser: (state) => (name) => {
		    return state.auth[name];
		},
		getAuthId: (state) => (name) => {
		    return state.auth[name];
		},
		getStoreId: (state) => {
		    return state.auth['store_id'];
		},
		getConfig: (state) => (name) => {
		    return state.config[name];
		},
	},
	plugins: [
		createPersistedState({ storage: window.sessionStorage })
	]
});

export default store;
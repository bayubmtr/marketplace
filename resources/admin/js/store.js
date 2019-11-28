import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'

const store = new Vuex.Store({
	state: {
		admin: {
			first_name: '',
			last_name: '',
			email: '',
			avatar: ''
		}
	},
	mutations: {
		setAuthUserDetail (state, admin) {
        	for (let key of Object.keys(admin)) {
                state.admin[key] = admin[key];
            }
            if ('avatar' in admin)
            	state.admin.avatar = admin.avatar !== null ? admin.avatar : 'avatar.png';
		},
		resetAuthUserDetail (state) {
        	for (let key of Object.keys(state.admin)) {
                state.admin[key] = '';
            }
		},
		setConfig (state, config) {
        	for (let key of Object.keys(config)) {
                state.config[key] = config[key];
            }
		}
	},
	actions: {
		setAuthUserDetail ({ commit }, admin) {
     		commit('setAuthUserDetail',admin);
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
		    return state.admin[name];
		},
		getAvatarUser: (state) => {
		    return state.admin['avatar'];
		},
		getAuthUserFullName: (state) => {
		    return state.admin['first_name']+' '+state.admin['last_name'];
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
// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

require('./bootstrap');
import router from './router/index'
import store from './store'
NProgress.configure({ showSpinner: false });
Vue.use({
  install(V) {
      let bus = new Vue();
      V.prototype.$bus = bus;
      V.bus = bus;
  }
});

/* eslint-disable no-new */
const app = new Vue({
  el: '#user',
  router,
  store
});

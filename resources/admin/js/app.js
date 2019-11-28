// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import "../../../node_modules/font-awesome/css/font-awesome.min.css"
import "../../../node_modules/simple-line-icons/css/simple-line-icons.css"
require('./bootstrap');
import store from './store'
import router from './routes'



/* eslint-disable no-new */
const app = new Vue({
  el: '#root',
  store,
  router
});

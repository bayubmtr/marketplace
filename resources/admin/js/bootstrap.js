import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import Form from './services/Form'
import BootstrapVue from 'bootstrap-vue'
import toastr from 'toastr'
import StarRating from 'vue-star-rating'
import VueLazyLoad from 'vue-lazyload'
import vSelect from 'vue-select'
import VuejsDialog from 'vuejs-dialog';
import NProgress from 'nprogress';
import 'vuejs-dialog/dist/vuejs-dialog.min.css';
import VueCountdown from '@xkeshi/vue-countdown';
import VueNumberInput from '@chenfengyuan/vue-number-input';
import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
const moment = require('moment')
require('moment/locale/id');
import { ModalPlugin } from 'bootstrap-vue'
import VueSession from 'vue-session'
import VS2 from 'vue-script2'
import JsonExcel from 'vue-json-excel'

window.Vue = Vue;
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(ModalPlugin)
Vue.component('star-rating', StarRating);
Vue.use(VueLazyLoad);
Vue.use(VuejsDialog);
Vue.component('v-select', vSelect);
window.NProgress = NProgress;
Vue.component(VueCountdown.name, VueCountdown);
Vue.component('number-input', VueNumberInput);
library.add(faUserSecret);
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.use(require('vue-moment'), {
    moment
})
Vue.component('downloadExcel', JsonExcel)
Vue.use(VueSession)
Vue.use(VS2)
window.toastr = toastr;
window.axios = axios;
window.Form = Form;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token_admin');

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

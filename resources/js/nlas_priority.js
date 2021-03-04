
window.Vue = require('vue');
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import Vuex from 'vuex';
import 'leaflet/dist/leaflet.css';

Vue.use(BootstrapVue);
Vue.use(Vuex);
Vue.component('nlaspriority', require('./components/NLASPriority.vue').default);
Vue.component('nlasmap', require('./components/NLASMap.vue').default);
Vue.component('prioritymap', require('./components/PriorityMap.vue').default);


const app = new Vue({
    el: '#nlas_priority',
});

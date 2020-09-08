
window.Vue = require('vue');


Vue.component('filters', require('./components/Filters.vue').default);
Vue.component('barchar', require('./components/Barchar.vue').default);



const app = new Vue({
    el: '#app',
});

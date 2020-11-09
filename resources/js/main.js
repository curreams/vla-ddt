
window.Vue = require('vue');
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import Vuex from 'vuex'


Vue.use(BootstrapVue);
Vue.use(Vuex);
Vue.component('filters', require('./components/Filters.vue').default);
Vue.component('spiner', require('./components/Spinner.vue').default);
Vue.component('barchart', require('./components/Barchart.vue').default);
Vue.component('linechart', require('./components/Linechart.vue').default);
Vue.component('piechart', require('./components/Piechart.vue').default);

const store = new Vuex.Store({
    state:{
        active_charts:[]
    },
    mutations:{
        add_active_charts(state, payload){
            state.active_charts.push(payload);
        },
        rem_active_charts(state, payload){
            const index = state.active_charts.indexOf(payload);
            if (index > -1) {
                state.active_charts.splice(index, 1);
            }
        }
    },
    actions:{

    },
    getters:{
        active_charts(state){
            return state.active_charts;
        }
    }


});

const app = new Vue({
    el: '#app',
    store: store,
});


window.Vue = require('vue');
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import Vuex from 'vuex';
import 'leaflet/dist/leaflet.css';

Vue.use(BootstrapVue);
Vue.use(Vuex);
Vue.component('maincomponent', require('./components/MainComponent.vue').default);
Vue.component('filters', require('./components/Filters.vue').default);
Vue.component('spiner', require('./components/Spinner.vue').default);
Vue.component('barchart', require('./components/Barchart.vue').default);
Vue.component('linechart', require('./components/Linechart.vue').default);
Vue.component('piechart', require('./components/Piechart.vue').default);
Vue.component('filterlist', require('./components/FilterList.vue').default);
Vue.component('activefilters', require('./components/ActiveFilters.vue').default);
Vue.component('savedsearches', require('./components/SavedSearches.vue').default);
Vue.component('heatmap', require('./components/HeatMap.vue').default);


const store = new Vuex.Store({
    state:{
        active_charts:[],
        selected_filters:[],
        show_saved_searches:false,
        saved_searches:[],
        map_data:null,
        location_display: [],
        providers: [],
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
        },
        update_selected_filters(state, selected_filters){
            state.selected_filters = selected_filters
        },
        add_selected_filters(state, selected_filter){
                state.selected_filters.push(selected_filter);
        },
        rem_selected_filters(state, selected_filter){
            const index = state.selected_filters.indexOf(selected_filter);
            if (index > -1) {
                state.selected_filters.splice(index, 1);
            }
        },
        update_show_saved_searches(state, show_saved_searches){
            state.show_saved_searches = show_saved_searches
        },
        update_saved_searches(state, saved_searches){
            state.saved_searches = saved_searches
        },
        update_map_data(state, map_data){
            state.map_data = map_data
        },
        add_location_display(state, filter){
                state.location_display.push(filter);
        },
        rem_location_display(state, filter){
            const index = state.location_display.indexOf(filter);
            if (index > -1) {
                state.location_display.splice(index, 1);
            }
        },
        update_location_display(state, filters){
            state.location_display = filters
        },
        add_providers(state, filter){
                state.providers.push(filter);
        },
        rem_providers(state, filter){
            const index = state.providers.indexOf(filter);
            if (index > -1) {
                state.providers.splice(index, 1);
            }
        },
        update_providers(state, filters){
            state.providers = filters
        },
    },
    actions:{

    },
    getters:{
        active_charts(state){
            return state.active_charts;
        },
        selected_filters(state){
            return state.selected_filters;
        },
        show_saved_searches(state){
            return state.show_saved_searches;
        },
        saved_searches(state){
            return state.saved_searches;
        },
        map_data(state){
            return state.map_data;
        },
        location_display(state){
            return state.location_display;
        },
        providers(state){
            return state.providers;
        }
    }


});

const app = new Vue({
    el: '#app',
    store: store,
});

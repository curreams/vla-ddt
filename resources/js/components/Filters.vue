<template>
    <div class="container">
        <div class="row">
            <div class="col-auto" v-for="filter_type in filter_types_var" :key="filter_type.id">
                <dropdown :class-name="'ddt-dropdown'" :is-icon="false">
                    <template slot="btn">
                        <label class="dropdown-toggle" :style="'color:'+ filter_type.color">
                            {{filter_type.name}}
                        </label>
                    </template>
                    <template v-if="filter_type.show_type.name == 'Expandable' " slot="body">
                        <div v-for="filter in filter_type.filters" :key="filter.id">
                            <div v-if="!filter.parent_id">
                                <dropdown   :trigger="'hover'" :role="'sublist'" :align="'right'">
                                    <template slot="btn">
                                        {{filter.value}}
                                    </template>
                                    <template slot="body">
                                        <div v-for="children in filter.children" :key="children.id">
                                            <b-form-checkbox :id="children.id+''" :name="children.id+''" v-model="selected_filters" :value="children" >{{children.value}} </b-form-checkbox>
                                        </div>
                                    </template>
                                </dropdown>
                            </div>
                        </div>
                    </template>
                    <template v-if="filter_type.show_type.name == 'Cramped' " slot="body">
                        <div>
                            <div v-for="filter in filter_type.filters" :key="filter.id">
                                <div v-if="!filter.parent_id">
                                    <b-form-checkbox :id="filter.id+''" :name="filter.id+''" v-model="selected_filters" :value="filter" >{{filter.value}} </b-form-checkbox>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div v-for="filter in filter_type.filters" :key="filter.id">
                                <div v-if="filter.parent_id">
                                    <b-form-checkbox :id="filter.id+''" :name="filter.id+''" v-model="selected_filters" :value="filter" >{{filter.value}} </b-form-checkbox>
                                </div>
                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>
        </div>
    </div>
</template>

<script>
    import DropdownMenu from '@innologica/vue-dropdown-menu';
    import Dropdown from 'bp-vuejs-dropdown';
    import EventBus from '../utils/event-bus';

    export default {
        components: { Dropdown },
        props: ['filter_types'],
        data() {
            return {
                filter_types_var: null,
                show: false,
                selected_filters: [],
                errors:null,
            };
        },
        methods: {
            loadFilters(){
                var self =this;
                self.filter_types_var = self.filter_types

            },
            searchData(val){
                var self = this;
                let emit = "";
                const  active_ch = self.active_charts
                Array.prototype.forEach.call(self.active_charts, active_chart => {
                    let url = "search/";
                    switch (active_chart) {
                        case 'linechart':

                        case 'barchart':
                            self.showLoader();
                            url = url+"getbarlinechart";
                            axios.post(url, val )
                            .then((response)=>{
                                EventBus.$emit("BARLINECHART", response.data);
                            })
                            .catch(error => {
                                if (typeof error.response.data === 'object') {
                                    self.errors = _.flatten(_.toArray(error.response.data.errors));
                                } else {
                                    self.errors = ['Something went wrong. Please try again.'];
                                }
                            })
                            .finally(() => {
                                self.hideLoader();
                            });

                            break;
                        case 'piechart':
                            self.showLoader();
                            url = url+"getpiechart";
                            axios.post(url, val )
                            .then((response)=>{
                                EventBus.$emit("PIECHART", response.data);
                            })
                            .catch(error => {
                                if (typeof error.response.data === 'object') {
                                    self.errors = _.flatten(_.toArray(error.response.data.errors));
                                } else {
                                    self.errors = ['Something went wrong. Please try again.'];
                                }
                            })
                            .finally(() => {
                                self.hideLoader();
                            });
                            break;
                        default:
                            break;
                    }

                    });

            },
            loadSearchListener(){
                var self = this;
                EventBus.$on('SEARCH', (data) => {
                    if(self.selected_filters.length > 0){
                        self.searchData(self.selected_filters);
                    }
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'filter');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'filter');
            }
        },
        watch:{
            selected_filters: function(val){
                var self = this;
                self.searchData(val);
            }
        },
        computed:{
            active_charts() {
                return this.$store.getters.active_charts;
            }
        },
        mounted() {
            this.loadFilters();
            this.loadSearchListener();
        }
    }
</script>

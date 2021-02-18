<template>
    <div class="container">
        <div class="row">
            <div class="col-auto" v-for="filter_type in filter_types" :key="filter_type.id">
                <dropdown :class-name="'ddt-dropdown'" :is-icon="false">
                    <template slot="btn">
                        <label class="dropdown-toggle" :style="'color:'+ filter_type.color">
                            {{filter_type.name}}
                        </label>
                    </template>
                    <template v-if="filter_type.show_type.name == 'Expandable' " slot="body">
                        <div v-for="filter in filter_type.filters" :key="filter.id">
                            <div v-if="!filter.parent_id">
                                <dropdown   :trigger="'click'" :role="'sublist'" :align="'right'">
                                    <template slot="btn">
                                        {{filter.value}}
                                    </template>
                                    <template slot="body">
                                        <filterlist :sa3="sa3" :sa4="sa4" :filter="filter" ></filterlist>
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
        <hr>
    </div>
</template>

<script>
    import DropdownMenu from '@innologica/vue-dropdown-menu';
    import Dropdown from 'bp-vuejs-dropdown';
    import EventBus from '../utils/event-bus';
    import VueSweetalert2 from 'vue-sweetalert2';
    Vue.use(VueSweetalert2);

    export default {
        components: { Dropdown,VueSweetalert2 },
        props:{
            filter_types:{
                type: Array,
                required: true
            },
            sa4:{
                type:Array,
                required:false
            },
            sa3:{
                type:Array,
                required:false
            }
        },
        data() {
            return {
                show: false,
                snapshot_by: "",
                errors:null,
            };
        },
        methods: {
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
                            if(self.snapshot_by !== ""){
                                self.showLoader();
                                let params = [val, self.snapshot_by];
                                url = url+"getpiechart";
                                axios.post(url, params )
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
                            }
                            break;
                        default:
                            break;
                    }

                    });

            },
            searchMapData(active_filters){
                var self = this;
                self.showLoader();
                let url = "search/getmapvalues";
                let params = [active_filters, "SA2"];
                axios.post(url,params)
                .then((response)=>{
                        self.map_data = response.data;
                        self.setmapControls();
                })
                .catch(error => {
                    this.$swal({
                            title: 'Oops...',
                            icon: 'error',
                            text: 'An error has occurred please contact your administrator',
                            showCloseButton: true,
                            });
                    if (typeof error.response.data === 'object') {
                        self.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        self.errors = ['Something went wrong. Please try again.', error.response];
                    }
                }).finally(() => {
                    self.hideLoader();
                });
            },
            loadSearchListener(){
                var self = this;
                EventBus.$on('SEARCH', (data) => {
                    if (typeof data === 'object' && data !== null){
                        self.snapshot_by = data.id;
                    }
                    if(self.selected_filters.length > 0){
                        self.searchData(self.selected_filters);
                    }
                });
            },
            setmapControls(){
                var self = this;
                self.$store.commit("update_location_display",[]);
                self.$store.commit("update_providers",[]);
                Array.prototype.forEach.call(self.selected_filters, filter => {
                    // Location
                    if(filter.filter_type == 1){
                        self.location_display = filter;
                    }
                });
                if(self.map_data.hasOwnProperty('Providers')){
                    self.providers = self.map_data.Providers;
                }
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
                self.searchMapData(val);
                self.searchData(val);
            }
        },
        computed:{
            active_charts() {
                return this.$store.getters.active_charts;
            },
            selected_filters:{
                get(){
                    return this.$store.getters.selected_filters;
                },
                set(value){
                    this.$store.commit("update_selected_filters",value);
                }
            },
            map_data:{
                get(){
                    return this.$store.getters.map_data;
                },
                set(value){
                    this.$store.commit("update_map_data",value);
                }
            },
            location_display:{
                get(){
                    return this.$store.getters.location_display;
                },
                set(value){
                    this.$store.commit("add_location_display",value);
                }
            },
            providers:{
                get(){
                    return this.$store.getters.providers;
                },
                set(value){
                    this.$store.commit("update_providers",value);
                }
            }
        },
        mounted() {
            this.loadSearchListener();
            this.searchMapData(this.selected_filters);
        }
    }
</script>

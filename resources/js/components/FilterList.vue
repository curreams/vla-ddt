<template>
    <div>
        <div v-if="filter.value == 'SA2'" class="row">
            <div class="col-auto container-multiselect" >
                <div>
                    <multiselect v-model="sa4_selected"
                            label="value"
                            key="surrogate_key"
                            :options="sa4"
                            :id="sa4"
                            track-by="surrogate_key"
                            placeholder="Select SA4"
                            :multiple="true"
                            :searchable="true"
                            :close-on-select="true"
                            :show-no-results="false"
                            :show-labels="false">
                    </multiselect>
                </div>
                <div>
                    <multiselect v-model="sa3_selected"
                            label="value"
                            key="surrogate_key"
                            :options="sa3"
                            :id="sa3"
                            track-by="surrogate_key"
                            placeholder="Select SA3"
                            :multiple="true"
                            :searchable="true"
                            :close-on-select="true"
                            :show-no-results="false"
                            :show-labels="false">
                    </multiselect>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <div v-show="sa_active(filter.value)" class="container-list">
                    <div class="col-list" v-for="(column, index) in columns" :key="index">
                        <div class="item-container-list" v-for="children in column" :key="children.id" >
                            <b-form-checkbox    checkbox :id="children.id+''" :name="children.id+''" v-model="selected_filters" :value="children" >{{children.value}} </b-form-checkbox>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import EventBus from '../utils/event-bus';

    export default {
        components: { Multiselect },
        props:{
            filter:{
                type: Object,
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
        data(){
            return {
                cols:1, // Number of columns by default
                //selected_filters: [],
                sa4_selected: [],
                sa3_selected: [],
                columns:[],
            }
        },
        methods: {
            calculateNumberColumns(listSize){
                var self = this;
                return Math.ceil(listSize/25);
            },
            calculateColumns(){
                var self = this;
                let data_columns = [];
                self.cols = self.calculateNumberColumns(self.filter.children.length);
                let mid = Math.ceil(self.filter.children.length / self.cols)
                for (let col = 0; col < this.cols; col++) {
                    data_columns.push(self.filter.children.slice(col * mid, col * mid + mid))
                }
                self.columns = data_columns;
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'filter');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'filter');
            },
            sa_active(filter_value){
                var self = this;
                if(filter_value == 'SA2' && self.sa3_selected.length === 0 && self.sa4_selected.length=== 0){
                    return false;
                } else {
                    return true;
                }
            }
        },
        computed:{
            selected_filters:{
                get(){
                    return this.$store.getters.selected_filters;
                },
                set(value){
                    this.$store.commit("update_selected_filters",value);
                }
            },
        },
        watch:{
            sa4_selected: function(val){
                var self = this;
                self.show_sa2 = false;
                val = val.concat(self.sa3_selected);
                let url = "filter/filterSA2";
                axios.post(url, val )
                .then((response)=>{
                    self.filter.children = response.data;
                    self.calculateColumns();
                    self.show_sa2 = true;
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        self.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        self.errors = ['Something went wrong. Please try again.'];
                    }
                }
                ).finally(() => {

                    });


            },
            sa3_selected: function(val){
                var self = this;
                val = val.concat(self.sa4_selected);
                let url = "filter/filterSA2";

                axios.post(url, val )
                .then((response)=>{
                    self.filter.children = response.data;
                    self.calculateColumns();
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        self.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        self.errors = ['Something went wrong. Please try again.'];
                    }
                }
                ).finally(() => {
                    });
            },
            selected_filters: function(val){
                var self = this;
                EventBus.$emit("SEARCH", "barchart");
            }
        },
        mounted() {
            this.calculateColumns();
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
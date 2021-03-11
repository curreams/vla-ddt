<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View pie graph  </b-button>
        <div class="row" v-if="visible">
            <div class="col-md-12" >
                <div class="pie-multiselect p-3">
                    <multiselect v-model="snapshot_selected"
                            label="name"
                            key="id"
                            :options="snapshot_by"
                            id="demographic_id"
                            track-by="id"
                            placeholder="Select client demographic"
                            :multiple="false"
                            :searchable="false"
                            :close-on-select="true"
                            :show-no-results="false"
                            :show-labels="false"
                            :allow-empty="false"
                            @input="onChange">
                    </multiselect>

                </div>
            </div>
            <div class="col-md-12" v-if="pie_chart_visible">
                <span class="bold">Breakdown of clients count by {{snapshot_selected.name.toLowerCase()}}</span>
                <br>
                <span>The pie chart displays the client count by {{snapshot_selected.name.toLowerCase()}}. The data is filtered by {{location_text}} {{sp_text}}. Also, filtered by {{aol_text}} {{demographic_text}} {{date_text}}</span>
            </div>
            <div class="col pie-chart" v-if="pie_chart_visible" >
                <pie-chart
                    class="pie-chart-comp"
                    v-if="char"
                    :data ="char.data"
                    :showLegend="char.showLegend"
                    :title="char.title"
                    :dataFormat="char.dataFormat"
                />
            </div>
        </div>


    </div>
</template>

<script>
    import  PieChart  from '@dpc-sdp/myvic-pie-chart';
    import EventBus from '../utils/event-bus';
    import Multiselect from 'vue-multiselect';
    export default {
        components: {PieChart, Multiselect},
        data() {
            return {
                visible:false,
                char: null,
                pie_char_data:null,
                snapshot_by:[
                    {id:'Age', name:"Age"},
                    {id:'DisabilityMental', name:"Disability And/or Mental Health"},
                    {id:'FinancialDisadvantage', name:"Financial Disadvantage"},
                    {id:'Gender', name:"Gender"},
                    {id:'Homeless', name:"Homeless"},
                    {id:'Indigenous', name:"Aboriginal And/or Torres Strait Islander"},
                    {id:'LOTE', name:"Language Other Than English"}
                ],
                snapshot_selected: null,
                pie_chart_visible: false,

            };
        },
        methods: {
            loadChartData(){
                var self =this;
                self.pie_chart_visible = false;
                self.char= {
                    title:"Clients demographic snapshot: " + (self.snapshot_selected ?  self.snapshot_selected.name: ""),
                    showLegend: true,
                    dataFormat: "normal",
                    data: {
                      /*  labels: [ "17-24","25-30"],
                        datasets: [
                            {
                                "label": "Service Provider 1",
                                "data": [73,27]
                            },

                        ]*/
                    },
                }
            },
            onChange (value) {
                var self = this;
                if(self.snapshot_selected){
                    self.char.title = "Clients demographic snapshot: " + self.snapshot_selected.name;
                    EventBus.$emit("SEARCH", self.snapshot_selected);
                }
            },
            changeVisible(){
                var self = this;
                self.visible = !self.visible;
                if(self.visible){
                    this.$store.commit("add_active_charts","piechart");
                    EventBus.$emit("SEARCH", self.snapshot_selected);
                } else {
                    this.$store.commit("rem_active_charts","piechart");
                }
            },
            updatePieChart(){
                var self = this;
                EventBus.$on('PIECHART', (data) => {
                    self.pie_char_data = data;
                    self.char.data = self.pie_char_data;
                    self.pie_chart_visible = false;
                    if(typeof self.char.data === 'object' && self.char.data !== null && self.char.data.hasOwnProperty('datasets')){
                        self.pie_chart_visible = true;
                    }

                });
            }
        },
        computed: {
            location_text:{
                get(){
                    return this.$store.getters.location_text;
                }
            },
            sp_text:{
                get(){
                    return this.$store.getters.sp_text;
                }
            },
            aol_text:{
                get(){
                    return this.$store.getters.aol_text;
                }
            },
            demographic_text:{
                get(){
                    return this.$store.getters.demographic_text;
                }
            },
            date_text:{
                get(){
                    return this.$store.getters.date_text;
                }
            },
        },
        mounted() {
            this.loadChartData();
            this.updatePieChart();
        }
    }
</script>

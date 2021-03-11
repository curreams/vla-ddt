<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View line graph  </b-button>
        <div class="card text-center" v-if="visible && !evaluateChartData()">
            <div class="card-body">
                <h5 class="card-title ddt-title">Nothing to display yet</h5>
                <p class="card-text">Refine the search to display the graph information.</p>
            </div>
        </div>
        <div v-if="visible && evaluateChartData()">
			<span class="bold">Clients serviced by location and service provider</span>
			<br>
			<span>The line graph displays the client count in {{location_text}} {{sp_text}}. The data is filtered by {{aol_text}} {{demographic_text}} {{date_text}}</span>
		</div>
        <div class="row" v-if="visible && evaluateChartData()">
            <div class="col" >
                <line-chart
                    v-if="char"
                    :data ="char.data"
                    :direction ="char.direction"
                    :showLegend="char.showLegend"
                    :title="char.title"
                    :dataFormat="char.dataFormat"
                />
            </div>
        </div>


    </div>
</template>

<script>
    import  LineChart from '@dpc-sdp/myvic-line-chart'
    import EventBus from '../utils/event-bus';
    export default {
        components: {LineChart},
        data() {
            return {
                visible:false,
                char: null,
                line_char_data:null,
            };
        },
        methods: {
            loadChartData(){
                var self =this;
                self.char= {
                    direction:"vertical",
                    title:"Clients By Service Provider",
                    showLegend: true,
                    dataFormat: "normal",
                    data: {
                        labels: [ 2015,2016],
                        datasets: [
                            {
                                "label": "Service Provider 1",
                                "data": [12,35]
                            },
                            {
                                "label":"Service Provider 2",
                                "data":[16, 44]
                            }

                        ]
                    },
                }
            },
            changeVisible(){
                var self = this;
                self.visible = !self.visible;
                if(self.visible){
                    this.$store.commit("add_active_charts","linechart");
                    EventBus.$emit("SEARCH", "linechart");
                } else {
                    this.$store.commit("rem_active_charts","linechart");
                }
            },
            updateLineChart(){
                var self = this;
                EventBus.$on('BARLINECHART', (data) => {
                    self.line_char_data = data;
                    self.char.data = self.line_char_data;
                });
            },
            evaluateChartData(){
                var self = this;
                if(Array.isArray(self.line_char_data)){
                    return false;

                } else if(self.line_char_data !== null && typeof self.line_char_data === 'object'){
                    if(Object.keys(self.line_char_data.datasets).length < 1 || Object.keys(self.line_char_data.datasets).length > 12){
                        return false;
                    }
                    return true;
                }
                return false;
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
            this.updateLineChart();
        }
    }
</script>

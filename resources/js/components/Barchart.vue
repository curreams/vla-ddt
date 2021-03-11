<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View column graph  </b-button>
        <div class="card text-center" v-if="visible && !evaluateChartData()">
            <div class="card-body">
                <h5 class="card-title ddt-title">Nothing to display yet</h5>
                <p class="card-text">Refine the search to display the graph information.</p>
            </div>
        </div>
        <div v-if="visible && evaluateChartData()">
			<span class="bold">Clients serviced by location and service provider</span>
			<br>
			<span>The column graph displays the client count in {{location_text}} {{sp_text}}. The data is filtered by {{aol_text}} {{demographic_text}} {{date_text}}</span>
		</div>
        <div class="row" v-if="visible && evaluateChartData()">
            <div class="col" >
                <bar-chart
                    v-if="char"
                    :data ="char.data"
                    :direction ="char.direction"
                    :showLegend="char.showLegend"
                    :title="char.title"
                    :dataFormat="char.dataFormat"
                    :stacked ="char.stacked"
                />
            </div>
        </div>


    </div>
</template>

<script>
    import  BarChart  from '@dpc-sdp/myvic-bar-chart';
    import EventBus from '../utils/event-bus';
    export default {
        components: {BarChart},
        data() {
            return {
                visible:false,
                char: null,
                bar_char_data:null,
            };
        },
        methods: {
            loadChartData(){
                var self =this;
                self.char= {
                    direction:"vertical",
                    title:"Clients By Service Provider",
                    stacked : false,
                    showLegend: true,
                    dataFormat: "normal",
                    data: {
                    },
                }
            },
            changeVisible(){
                var self = this;
                self.visible = !self.visible;
                if(self.visible){
                    this.$store.commit("add_active_charts","barchart");
                    EventBus.$emit("SEARCH", "barchart");
                } else {
                    this.$store.commit("rem_active_charts","barchart");
                }
            },
            updateBarChart(){
                var self = this;
                EventBus.$on('BARLINECHART', (data) => {
                    self.bar_char_data = data;
                    self.char.data = self.bar_char_data;
                });
            },
            evaluateChartData(){
                var self = this;
                if(Array.isArray(self.bar_char_data)){
                    return false;

                } else if(self.bar_char_data !== null && typeof self.bar_char_data === 'object'){
                    if(Object.keys(self.bar_char_data.datasets).length < 1 || Object.keys(self.bar_char_data.datasets).length > 12){
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
            this.updateBarChart();
        }
    }
</script>

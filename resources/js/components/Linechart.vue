<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View line graph  </b-button>
        <div class="row" v-if="visible">
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
            }
        },
        mounted() {
            this.loadChartData();
            this.updateLineChart();
        }
    }
</script>

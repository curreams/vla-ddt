<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View pie graph  </b-button>
        <div class="row" v-if="visible">
            <div class="col pie-chart" >
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
    export default {
        components: {PieChart},
        data() {
            return {
                visible:false,
                char: null,
                pie_char_data:null,
            };
        },
        methods: {
            loadChartData(){
                var self =this;
                self.char= {
                    title:"Clients demographic snapshot:Age",
                    showLegend: true,
                    dataFormat: "normal",
                    data: {
                        labels: [ "17-24","25-30"],
                        datasets: [
                            {
                                "label": "Service Provider 1",
                                "data": [73,27]
                            },

                        ]
                    },
                }
            },
            changeVisible(){
                var self = this;
                self.visible = !self.visible;
                if(self.visible){
                    this.$store.commit("add_active_charts","piechart");
                    EventBus.$emit("SEARCH", "piechart");
                } else {
                    this.$store.commit("rem_active_charts","piechart");
                }
            },
            updatePieChart(){
                var self = this;
                EventBus.$on('PIECHART', (data) => {
                    self.pie_char_data = data;
                    self.char.data = self.pie_char_data;
                });
            }
        },
        mounted() {
            this.loadChartData();
            this.updatePieChart();
        }
    }
</script>

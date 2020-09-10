<template>
    <div class="container">
        <div class="row">
            <div class="col">
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
    import  BarChart  from '@dpc-sdp/myvic-bar-chart'
    export default {
        components: {BarChart},
        data() {
            return {
                char: null,
            };
        },
        methods: {
            loadCharData(){
                var self =this;
                self.char= {
                    direction:"vertical",
                    title:"Gender",
                    stacked : false,
                    showLegend: true,
                    dataFormat: "normal",
                    data: {
                        labels: [ "Male","Female","Other"],
                        datasets: [
                            {
                            "label": "Gender",
                            "data": [7341,11736,159]
                            },

                        ]
                    },
                }
            }
        },
        mounted() {
            this.loadCharData();
        }
    }
</script>

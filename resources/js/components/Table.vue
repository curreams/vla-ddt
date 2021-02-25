<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View table</b-button>
        <div class="row" v-if="visible">
            <div class="col" >
                <table class="sploc-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th colspan="2" v-for="year in table_graph.years" :key="year">{{year}}</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="table-red" colspan="2" v-for="year in table_graph.years" :key="year">Client Count</th>
                        </tr>
                        <tr class="table-red">
                            <th>SA2</th>
                            <th>Service Provider</th>
                            <th v-for="th in createYearsth()" :key="th.id" >{{th.value}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(providers, loc_name) in table_graph.data" >
                            <tr v-for="(provider, p_name) in providers" :key="p_name + (Math.random()*1000)">
                                <td v-if="isfirstIndex(providers, p_name)" :rowspan="getProviders(providers, true)">{{loc_name}}</td>
                                <td>{{p_name}}</td>
                                <template v-for="(total, y_name) in provider">
                                    <td :key="y_name + (Math.random()*1000)" >{{total}}</td>
                                    <td :key="y_name + (Math.random()*1000)">{{roundNumber((parseInt(total) * 100) / table_graph.total)  }}</td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</template>

<script>

    import EventBus from '../utils/event-bus';
    export default {
        components: {},
        data() {
            return {
                visible:false,

            };
        },
        methods: {
            createYearsth(){
                var self = this;
                let result = [];
                let years = []
                if(self.table_graph.years){
                    years = Object.values(self.table_graph.years);
                    for(let i=0; i< years.length; i++){
                        result.push({"id":i,
                            "value":"n",
                            });
                        result.push({"id": (i +0.5) ,
                            "value":"%",
                        });
                    }
                }
                return result;
            },
            getProviders(providers, consult_length = false){
                var self =this;
                let result = [];
                let provider_names = Object.keys(providers);
                if(consult_length){
                    return provider_names.length;
                }/*
                provider_names.forEach(provider_name => {
                    result.push(providers[provider_name]);
                });
                console.log("getP",typeof(result));*/
                result["juego"]= "juego";
                return result;
            },
            isfirstIndex(providers, p_name){
                var self =this;
                let result = [];
                let provider_names = Object.keys(providers);
                if(provider_names){
                    if(provider_names[0].localeCompare(p_name) == 0){
                        console.log("Entrando aca" + p_name );
                        return true;
                    }
                }
                return false;
            },
            changeVisible(){
                var self = this;
                self.visible = !self.visible;
                if(self.visible){
                    this.$store.commit("add_active_charts","table");
                    EventBus.$emit("SEARCH", "table");
                } else {
                    this.$store.commit("rem_active_charts","table");
                }
            },
            roundNumber(number){
                return number.toFixed(2)
            }
        },
        computed: {
            table_graph:{
                get(){
                    return this.$store.getters.table_graph;
                },
                set(value){
                    this.$store.commit("update_table_graph",value);
                }
            },
        },
        mounted() {
            //this.updateTable();
        }
    }
</script>

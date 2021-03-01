<template>
    <div class="container">
        <b-button @click="changeVisible" class="graph-button-section">View table</b-button>
        <div class="row" v-if="visible">
            <div class="col" >
                <table id="graphtable" class="sploc-table">
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
                                <td v-if="isfirstIndex(providers, p_name)" :rowspan="getProvidersLength(providers)">{{loc_name}}</td>
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
        <br>
        <div class="row" v-if="visible">
            <div class="col" >
                <b-button @click="exportTable()" class="export-data-button">Export data</b-button>
            </div>
        </div>



    </div>
</template>

<script>

    import EventBus from '../utils/event-bus';
    import TableExport from 'tableexport';
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
            getProvidersLength(providers){
                var self =this;
                let result = [];
                let provider_names = Object.keys(providers);
                return provider_names.length;


            },
            isfirstIndex(providers, p_name){
                var self =this;
                let result = [];
                let provider_names = Object.keys(providers);
                if(provider_names){
                    if(provider_names[0].localeCompare(p_name) == 0){
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
            },
            exportTable(){
                var self = this;
                let table = TableExport(document.getElementById("graphtable"), {
                    headers: true,                      // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
                    footers: false,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
                    formats: ["xlsx"],    // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
                    filename: "VLA_DDT_Data_export",                     // (id, String), filename for the downloaded file, (default: 'id')
                    bootstrap: true,                   // (Boolean), style buttons using bootstrap, (default: true)
                    exportButtons: false,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
                    position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
                    trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
                    sheetname: "DDT"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
                });
                let export_data = table.getExportData();
                let xlsxData = export_data.graphtable.xlsx;
                table.export2file(xlsxData.data, xlsxData.mimeType, xlsxData.filename, xlsxData.fileExtension, xlsxData.merges, xlsxData.RTL, xlsxData.sheetname);
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

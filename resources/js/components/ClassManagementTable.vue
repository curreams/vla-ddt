<template>
<div>
    <div class="pull-right">
    <button v-on:click="submitInfo()" class="btn btn-success">Select as filter</button>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Select</th>
            <th>Id</th>
            <th>Value</th>
            <th>Table</th>
            <th>Filter Type</th>
        </tr>

        <tr v-for="dimension in dimensions" :key="dimension.id">
        <td><input type="checkbox" v-model="dimension.selected" name="dimensions[]" :value="dimension.id + dimension.filter_type" />  </td>
        <td> {{dimension.surrogate_key}}</td>
        <td>{{dimension.value}}</td>
        <td>{{dimension.table}}</td>
        <td>
            <select v-model="dimension.filter_type" :disabled="!dimension.selected">
                <option disabled value="">Please select one</option>
                <option v-for="filter_type in filter_types" :key="filter_type.id">{{filter_type.name}}</option>
            </select>
        </td>

        </tr>
    </table>
</div>
</template>

<script>

    export default {
        props: ['class_dimensions', 'filter_types'],
        data() {
            return {
                dimensions: null,
                errors:null,
            };
        },
        methods: {
            loadDimensions()
            {
                var self = this;
                self.dimensions = self.class_dimensions;
            },
            submitInfo(){
                var self = this;
                //console.log(self.dimensions);
                axios.post('/class', self.dimensions )
                .then((response)=>{
                    console.log(response);
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        self.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        self.errors = ['Something went wrong. Please try again.'];
                    }
                });

            }


        },
        mounted() {
            this.loadDimensions();
        }
    }
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-auto" v-for="filter_type in filter_types_var" :key="filter_type.id">
                <dropdown-menu
                        v-model="filter_type.display">
                    <button class="ddt-filter-button dropdown-toggle" :style="'color:'+ filter_type.color">
                        {{filter_type.name}}
                    </button>
                    <div slot="dropdown">
                        <div>
                            <div v-for="filter in filter_type.filters" :key="filter.id">
                                <b-form-checkbox v-if="!filter.parent_id" :id="filter.id+''" :name="filter.id+''" v-model="selected_filters" :value="filter" >{{filter.value}} </b-form-checkbox>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div v-for="filter in filter_type.filters" :key="filter.id">
                                <b-form-checkbox v-if="filter.parent_id" :id="filter.id+''" :name="filter.id+''" v-model="selected_filters" :value="filter" >{{filter.value}} </b-form-checkbox>
                            </div>
                        </div>
                    </div>
                </dropdown-menu>
            </div>
        </div>
    </div>
</template>

<script>
    import DropdownMenu from '@innologica/vue-dropdown-menu';
    export default {
        props: ['filter_types'],
        data() {
            return {
                filter_types_var: null,
                show: false,
                selected_filters: [],
            };
        },
        methods: {
            loadFilters(){
                var self =this;
                self.filter_types_var = self.filter_types

            }
        },
        mounted() {
            this.loadFilters();
        }
    }
</script>

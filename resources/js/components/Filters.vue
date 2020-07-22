<template>
    <div class="container">
        <div class="row">
            <div class="col" v-for="filter_type in filter_types" :key="filter_type.id">
                <dropdown-menu
                        v-model="show">
                    <button class="ddt-filter-button dropdown-toggle" :style="'color:'+ filter_type.color">
                        {{filter_type.name}}
                    </button>
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
                show: false,
            };
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

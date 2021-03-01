<template>
    <div class="container">
        <div class="row">
            <div class="col-auto">
                <span class="span-active-filter" >Active Filters</span>
                <button class="button-clear-active-filter" v-on:click="clearFilters"> Clear filters</button>
                <button :disabled="selected_filters.length <= 0" v-on:click="saveFilters" class="button-save-active-filter"> Save search</button>
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <vue-tags-input
                    v-model="tag"
                    :tags="tags"
                    disabled
                    placeholder=""
                    @tags-changed="newTags => tags = newTags"
                />
            </div>
        </div>
        <hr>
    </div>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';
    import EventBus from '../utils/event-bus';
    import VueSweetalert2 from 'vue-sweetalert2';

    Vue.use(VueSweetalert2);

    export default {
        components: { VueTagsInput, VueSweetalert2 },
        data() {
            return {
                tag: '',
                tags:[],
                errors:null,
            };
        },
        computed:{
            selected_filters(){
                return this.$store.getters.selected_filters;
            }
        },
        methods:{
            clearFilters(){
                var self=this;
                this.$store.commit("update_selected_filters",[]);
                self.tags= [];
            },
            saveFilters(){
                var self = this;
                self.showLoader();
                let url = "saved/store";
                axios.post(url, self.selected_filters)
                .then((response)=>{
                        this.$swal({
                                title: 'Success',
                                icon: 'success',
                                text: response.data,
                                showCloseButton: true,
                                });

                })
                .catch(error => {
                    this.$swal({
                            title: 'Oops...',
                            icon: 'error',
                            text: 'An error has occurred please contact your administrator',
                            showCloseButton: true,
                            });
                    if (typeof error.response.data === 'object') {
                        self.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        self.errors = ['Something went wrong. Please try again.', error.response];
                    }
                })
                .finally(() => {
                    self.hideLoader();
                });
            },
            showLoader() {
                EventBus.$emit('SHOW_LOADER', 'filter');
            },
            hideLoader() {
                EventBus.$emit('HIDE_LOADER', 'filter');
            }

        },
        watch:{
            selected_filters: function(val){
                var self = this;
                self.tags = [];
                if(self.selected_filters != null) {
                    Array.prototype.forEach.call(self.selected_filters, selected_filter => {
                        let style = 'background: transparent; border: 1px solid '+ selected_filter.color+'; color: '+selected_filter.color+'; margin-right: 5px; border-radius: 10px;';
                        self.tags.push({
                            "text": selected_filter.value,
                            "style": style
                        });

                    });
                }
            }
        },

    }
</script>
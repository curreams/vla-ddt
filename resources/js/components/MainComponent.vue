<template>
	<div class="main-div">
		<button  v-on:click="loadSavedSearches" class="button-show-saved-seach pull-right"> {{save_sarches_button_text}}</button>
		<div class="main-div" v-show="!show_saved_searches">
			<activefilters></activefilters>
			<filters :filter_types="filter_types" :sa4="sa4" :sa3="sa3"></filters>
			<heatmap :filter_types="filter_types"></heatmap>
			<tablechart></tablechart>
			<barchart></barchart>
			<linechart></linechart>
			<piechart></piechart>
		</div>
		<div class="main-div" v-show="show_saved_searches">
			<savedsearches :filter_types="filter_types"></savedsearches>
		</div>
	</div>
</template>

<script>

import EventBus from '../utils/event-bus';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueSweetalert2);

export default {
	components: { VueSweetalert2 },
	props:{
		filter_types:{
			type: Array,
			required: true
		},
		sa4:{
			type:Array,
			required:false
		},
		sa3:{
			type:Array,
			required:false
		}

	},
	data(){
		return {
			save_sarches_button_text:"Saved searches",
		}
	},
	methods:{
		loadSavedSearches() {
			var self = this;
			self.show_saved_searches = !self.show_saved_searches;
			if(self.show_saved_searches){
				self.save_sarches_button_text = "Go back";
				let url = "saved/get";
                axios.get(url)
                .then((response)=>{
					if(response.data){
						self.saved_searches = response.data;
					}
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
			} else {
				self.save_sarches_button_text = "Saved searches";
			}


		},
		showLoader() {
			EventBus.$emit('SHOW_LOADER', 'filter');
		},
		hideLoader() {
			EventBus.$emit('HIDE_LOADER', 'filter');
		},
	},
	computed:{
		saved_searches:{
			get(){
				return this.$store.getters.saved_searches;
			},
			set(value){
				this.$store.commit("update_saved_searches",value);
			}
		},
		show_saved_searches:{
			get(){
				return this.$store.getters.show_saved_searches;
			},
			set(value){
				this.$store.commit("update_show_saved_searches",value);
			}
		},
	}
};
</script>

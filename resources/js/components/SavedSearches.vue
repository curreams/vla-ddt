<template>
	<div class="container">
		<div class="row">
			<div class="col-lg-12 margin-tb">
				<div class="pull-left">
					<h2>Saved Searches</h2>
				</div>
			</div>
		</div>
		<div v-for="saved_search in saved_searches" :key="saved_search.id">
			DATE SAVED: {{saved_search.created_at}}
			<div class="col-auto" v-for="filter_type in filter_types" :key="filter_type.id">
				<span :style="'color:'+ filter_type.color">
					{{filter_type.name}}:
						<span v-for="filter in saved_search.filters" :key="filter.id">
							<span  v-if="filter.filter_type == filter_type.id">
								{{filter.value}},
							</span>
						</span>
				</span>
			</div>
			<button v-on:click="loadFilters(saved_search)" class="button-save-active-filter">Load</button>
			<button class="button-clear-active-filter" v-on:click="deleteSearch(saved_search.id)">Delete</button>
			<hr>
		</div>
	</div>
</template>

<script>

	import EventBus from '../utils/event-bus';
	import VueSweetalert2 from 'vue-sweetalert2';
	Vue.use(VueSweetalert2);

export default {
	components: {VueSweetalert2
	},
	props:{
		filter_types:{
			type: Array,
			required: true
		},
	},
	data() {
		return {
			data:null
		};
	},
	methods:{
		deleteSearch(search_id){
			var self = this;
			self.showLoader();
			let url = "saved/remove/"+search_id;
			axios.post(url)
			.then((response)=>{
				this.$swal({
					title: 'Success',
					icon: 'success',
					text: "Saved search deleted successfully",
					showCloseButton: true,
					}).then(function(){
					self.saved_searches = response.data;
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
		},
		loadFilters(saved_search) {
			var self = this;
			self.selected_filters = saved_search.filters;
			EventBus.$emit("SEARCH", "Load Saved Search");
		}

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
		selected_filters:{
			get(){
				return this.$store.getters.selected_filters;
			},
			set(value){
				this.$store.commit("update_selected_filters",value);
			}
		},
	}
};
</script>

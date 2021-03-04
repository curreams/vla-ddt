<template>
	<div class="main-div">
		<div class="container text-center text-md-left">
			<div class="row">
				<div class="col-md-6 mb-md-0 mb-3">
					<h5 class="ddt-footer-title">NLAS Indicators</h5>
					<p>The Need for Legal Assistance (NLAS) indicators are proxy measures of the potential need for Legal
						services by location, using the Australian Bureau of Statistics Census 2016
					</p>
					<b-button @click="changeVisibleNLAS"  class="font-size-medium graph-button-section">View NLAS indicators data for region of interest</b-button>
				</div>
				<div class="col-md-6 mb-md-0 mb-3">
					<h5 class="ddt-footer-title">Priority Groups</h5>
					<p>Priority groups are categories of people identified in the National Legal
						Partership Agreement who are more likely to require assistance to resolve
						legal problems because of vulnerability and/or disadvantage
					</p>
					<p>The demographic data on priority groups is captured in the Australian Bureau of
						Statistics Cencus 2016
					</p>
					<b-button @click="changeVisiblePriority" class="font-size-medium graph-button-section">View priority group data for region of interest</b-button>
				</div>
			</div>
		</div>
		<div class="main-div" v-if="visible_nlas">
			<nlasmap></nlasmap>
		</div>
		<div class="main-div" v-if="visible_priority">

		</div>
	</div>
</template>

<script>

import EventBus from '../utils/event-bus';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueSweetalert2);

export default {
	components: { VueSweetalert2 },
	data(){
		return {
			visible_nlas:false,
			visible_priority:false
		}
	},
	methods:{
		changeVisibleNLAS(){
			var self = this;
			self.visible_nlas = !self.visible_nlas;
		},
		changeVisiblePriority(){
			var self = this;
			self.visible_priority = !self.visible_priority;
		},
		showLoader() {
			EventBus.$emit('SHOW_LOADER', 'filter');
		},
		hideLoader() {
			EventBus.$emit('HIDE_LOADER', 'filter');
		},
	},
};
</script>

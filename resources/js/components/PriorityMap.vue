<template>

	<div class="contaniner">
		<l-map
			ref="map_priority"
			:zoom="zoom"
			:center="center"
			style="height: 500px; width: 100%"
		>
			<l-tile-layer
			:url="url"
			:attribution="attribution"
			/>
			<l-geo-json
			:geojson="map_data"
			:options="options"
			:options-style="styleFunction"
			:visible="map_visibility"
			/>
			<l-control position="topright">
			<table class="sa2-table">
				<thead>
					<tr>
						<th colspan="3" >
							<strong>
							<span v-if="property === ''" class="table-title">No priority group selected</span>
							<span v-if="property !== ''" class="table-title">{{property_name}}</span>
							</strong>
										<br>
										<span class="table-title-client">Total people count <span v-if="map_data">{{map_data.total}}</span><span v-else>0</span></span></th>
					</tr>
					<tr v-if="location_display.length>0"  class="table-subtitle">
						<th >LGA</th>
						<th colspan="2">People count</th>
					</tr>
					<tr v-if="location_display.length>0">
						<th> </th>
						<th>n</th>
						<th>%</th>
					</tr>
				</thead>
				<tbody v-if="location_display.length>0">
				<tr  v-for="location in location_display" :key="location.lga_code">
					<th>{{location.lga}}</th>
					<th>{{location[property]}}</th>
					<th>{{roundNumber((location[property] * 100) / map_data.total)}}</th>
				</tr>
				</tbody>
			</table>
			</l-control>

			<l-control	position="bottomright">
			<table class="tg">
				<tbody>
				<tr v-for="grade in grades" :key="grade.color">
					<td class="tg-value">{{grade.value}}</td>
					<td class="tg-color" :style="'background-color:' + grade.color"></td>
				</tr>
				</tbody>
			</table>
			</l-control>
		</l-map>
		<div class="row pt-3">
			<span class="col-md-2 ddt-title">Priority group:</span>
			<multiselect v-model="priority_selected"
				label="name"
				key="id"
				:options="priority_groups"
				id="priority_id"
				track-by="id"
				placeholder="Select one"
				:multiple="false"
				:searchable="false"
				:close-on-select="true"
				:show-no-results="false"
				:show-labels="false"
				:allow-empty="false"
				class="nlas-priority-multiselect col-md-6"
				@input="onChange">
			</multiselect>
		</div>
	</div>
</template>

<script>
import { latLng} from "leaflet";
import { LMap, LTileLayer, LMarker, LGeoJson,LControl } from "vue2-leaflet";
import VueSweetalert2 from 'vue-sweetalert2';
import Multiselect from 'vue-multiselect';
import EventBus from '../utils/event-bus';
Vue.use(VueSweetalert2);



export default {
	components: {
		LMap,
		LTileLayer,
		LGeoJson,
		LMarker,
		LControl,
		VueSweetalert2,
		Multiselect
	},
	data() {
		return {
			map_bounds:null,
			map_data:[],
			property:"",
			property_name:"",
			location_display:[],
			zoom: 7,
			center: [-37.02100, 144.964600],
			fillColor: "#007467",
			url: 'https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/{z}/{x}/{y}?access_token='+ process.env.MIX_MAPBOX_TOKEN,
			attribution:'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
					'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			map_visibility:false,
			priority_groups:[
				{id:'ATSI_peoples(14_and_under)', name:"ATSI people (14 and under)"},
				{id:'ATSI_peoples(15+)', name:"ATSI people (15 and over)"},
				{id:'ATSI_peoples_with_personal_income<52K(15+)', name:"ATSI people with personal income <$52K (15 and over)"},
				{id:'CALD_people(15+)', name:"CALD people (15 and over)"},
				{id:'children_and_youth(0-24)', name:"Children and youth (0-24)"},
				{id:'disengaged_youth(15-24)', name:"Disengaged youth (15-24)"},
				{id:'older_people(65+)', name:"Older people (65 and over)"},
				{id:'people_experiencing_homelessness(all_ages)', name:"People experiencing homelessness (all ages)"},
				{id:'people_living_in_rural_or_remote_areas(all_ages)', name:"People living in rural or remote areas (all ages)"},
				{id:'people_who_did_not_access_internet_from_dwelling_(15+)', name:"People who did not access the internet from their dwelling (15 and over)"},
				{id:'people_who_did_not_access_internet_from_dwelling(all_ages)', name:"People who did not access the internet from their dwelling (all ages)"},
				{id:'people_with_a_disability(14_and_under)', name:"People with disability (14 and under)"},
				{id:'People_with_a_disability(15-64)', name:"People with disability (15-64)"},
				{id:'People_with_a_household_income<26K(all ages)', name:"People with an equivalised household income <$26K (all ages)"},
				{id:'people_with_a_lower_education(15-64)', name:"People with a lower education (15-64)"},
				{id:'people_with_a_personal_income<26K(15+)', name:"People with personal income <$26K (15 and over)"},
				{id:'people_with_a_personal_income<52K(15+)', name:"People with personal income <$52K (15 and over)"},
				{id:'people_with_poor_english_proficiency(15+)', name:"People with poor english proficiency (15 and over)"},
				{id:'population(15+)', name:"Population (15 and over)"},
				{id:'population(15-64)', name:"Population (15-64)"},
				{id:'population(all)', name:"Population (all)"},
				{id:'single_parents(15)', name:"Single parents (15 and over)"},
				{id:'Unemployed_people(15+)', name:"Unemployed people (15 and over)"},

			],
			priority_selected: {},
			grades : [ {"value":"1000",
						"color": "#3e2241"},
						{
						"value":"500",
						"color": "#5e3461"
						},
						{
						"value":"200",
						"color": "#7d4582"
						},
						{
						"value":"100",
						"color": "#9d57a3"
						},
						{
						"value":"50",
						"color": "#b078b5"
						},
						{
						"value":"20",
						"color": "#c49ac7"
						},
						{
						"value":"10",
						"color": "#d7bbda"
						},
						{
						"value":"1",
						"color": "#ebddec"
						},
						{
						"value":"No data",
						"color": "#8a8884"
						}],

		};

	},
	methods:{
		searchMapData(){
			var self = this;
			self.showLoader();
			let url = "priority/getprioritymapdata";
			axios.post(url, {'property':self.property})
			.then((response)=>{
					self.map_data = response.data;
					//self.setmapControls();
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
			}).finally(() => {
				self.hideLoader();
			});
		},
		getColor(data){
			return 	data > 1000 ? '#3e2241' :
					data > 500  ? '#5e3461' :
					data > 200  ? '#7d4582' :
					data > 100  ? '#9d57a3' :
					data > 50   ? '#b078b5' :
					data > 20   ? '#c49ac7' :
					data > 10   ? '#d7bbda' :
					data >= 1   ? '#ebddec' :
					'#8a8884';
		},
		zoomFeature(event){
			var self = this;
			self.$refs.map_priority.mapObject.fitBounds(event.target.getBounds());
			self.addToLocation(event.target.feature.properties);

		},
		addToLocation(location){
			var self = this;
			if(self.location_display.includes(location) ){
				const index = self.location_display.indexOf(location);
				if (index > -1) {
					self.location_display.splice(index, 1);
				}
			}else {
				self.location_display.push(location);
			}

		},
		onChange(){
			var self = this;
			self.location_display=[];
			self.property = self.priority_selected.id;
			self.property_name = self.priority_selected.name;
			self.map_visibility = true;
			self.$refs.map_priority.mapObject.fitBounds(self.map_bounds);
			self.searchMapData();
		},
		roundNumber(number){
			return number.toFixed(2);
		},
		getMapbounds(){
			var self = this;
			self.map_bounds = self.$refs.map_priority.mapObject.getBounds();
		},
		showLoader() {
			EventBus.$emit('SHOW_LOADER', 'filter');
		},
		hideLoader() {
			EventBus.$emit('HIDE_LOADER', 'filter');
		}

	},
	computed: {
		options() {
			return {
			onEachFeature: this.onEachFeatureFunction,
			};
		},
		styleFunction() {
			var self = this;
			const fillColor = this.fillColor; // important! need touch fillColor in computed for re-calculate when change fillColor
			return (feature, layer) => {
				return {
					weight: 2,
					color: "white",
					opacity: 1,
					fillOpacity: 0.7,
					fillColor: self.getColor(feature.properties[self.property]) ,//fillColor,
				};
			};
		},
		onEachFeatureFunction() {
			var self = this;
			return (feature, layer) => {
				if(!feature.properties[self.property]){
					feature.properties[self.property] = "No data";
				}
				layer.bindTooltip(
					"<div><span style='color:#971a4b;font-weight: bold'>" +
					feature.properties.lga +
					"</span></div>" +
					feature.properties[self.property] +  " people",
					{ permanent: false, sticky: true }
				);
				layer.on('click', (event) =>{
					self.zoomFeature(event);
				}

				);
			};
		},
	},
	mounted() {
            this.getMapbounds();
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
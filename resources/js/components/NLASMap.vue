<template>

	<div class="contaniner">
		<l-map
			ref="map_nlas"
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
							<span v-if="property === ''" class="table-title">No NLAS indicator selected</span>
							<span v-if="property !== ''" class="table-title">{{property}}</span>
							</strong>
										<br>
										<span class="table-title-client">Total client count <span v-if="map_data">{{map_data.total}}</span><span v-else>0</span></span></th>
					</tr>
					<tr v-if="location_display.length>0"  class="table-subtitle">
						<th >LGA</th>
						<th colspan="2">Client count</th>
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
			<span class="col-md-2 ddt-title">NLAS indicator:</span>
			<multiselect v-model="nlas_selected"
				label="name"
				key="id"
				:options="nlas_indicators"
				id="nlas_id"
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
			all_map_data: [],
			map_data:[],
			property:"",
			location_display:[],
			zoom: 7,
			center: [-37.02100, 144.964600],
			fillColor: "#007467",
			url: 'https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/{z}/{x}/{y}?access_token='+ process.env.MIX_MAPBOX_TOKEN,
			attribution:'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
					'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			map_visibility:false,
			nlas_indicators:[
				{id:'NLAS(52K)', name:"NLAS($52K)"},
				{id:'NLAS(65+)', name:"NLAS(65 and over)"},
				{id:'NLAS(ATSI)', name:"NLAS(ATSI)"},
				{id:'NLAS(CALD)', name:"NLAS(CALD)"},
				{id:'NLAS(Capability)', name:"NLAS(Capability)"},
				{id:'Population(15+)', name:"Population(15 and over)"},
				{id:'Population(15-64)', name:"Population(15-64)"},
				{id:'Population(65+)', name:"Population(65 and over)"},
				{id:'Population(all)', name:"Population(all)"},

			],
			nlas_selected: {},
			grades : [ {"value":"1000",
						"color": "#74218f"},
						{
						"value":"500",
						"color": "#8e28af"
						},
						{
						"value":"200",
						"color": "#a830cf"
						},
						{
						"value":"100",
						"color": "#b54fd6"
						},
						{
						"value":"50",
						"color": "#c36fde"
						},
						{
						"value":"20",
						"color": "#d08fe5"
						},
						{
						"value":"10",
						"color": "#ddafed"
						},
						{
						"value":"1",
						"color": "#ebcff4"
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
			let url = "nlas/getnlasmapdata";
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
			return 	data > 1000 ? '#74218f' :
					data > 500  ? '#8e28af' :
					data > 200  ? '#a830cf' :
					data > 100  ? '#b54fd6' :
					data > 50   ? '#c36fde' :
					data > 20   ? '#d08fe5' :
					data > 10   ? '#ddafed' :
					data >= 1   ? '#ebcff4' :
					'#8a8884';
		},
		zoomFeature(event){
			var self = this;
			self.$refs.map_nlas.mapObject.fitBounds(event.target.getBounds());
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
			self.property = self.nlas_selected.id;
			self.map_visibility = true;
			self.$refs.map_nlas.mapObject.fitBounds(self.map_bounds);
			self.searchMapData();
		},
		roundNumber(number){
			return number.toFixed(2);
		},
		getMapbounds(){
			var self = this;
			self.map_bounds = self.$refs.map_nlas.mapObject.getBounds();
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
					feature.properties[self.property] +  " clients",
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
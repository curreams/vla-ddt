<template>

	<div class="contaniner">
		<div>
			<span class="section-title">Legal Services</span>
		</div>

		<l-map
			ref="map"
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
			/>
			<l-control position="topright">
			<table class="sa2-table">
				<thead>
					<tr>
						<th colspan="3" >
							<strong>
							<span v-if="location_display.length < 1" class="table-title">No location selected</span>
							<span v-if="location_display.length == 1" class="table-title">{{location_display[0].value}}</span>
							<span v-if="location_display.length > 1" class="table-title">Two or more locations selected</span>
							</strong>
										<br>
										<span class="table-title-client">Total client count <span v-if="map_data">{{map_data.Total}}</span><span v-else>0</span></span></th>
					</tr>
					<tr v-if="providers.length > 0" class="table-subtitle">
						<th >Service provider</th>
						<th colspan="2">Client count</th>
					</tr>
					<tr v-if="providers.length > 0">
						<th> </th>
						<th>n</th>
						<th>%</th>
					</tr>
				</thead>
				<tbody v-if="providers.length > 0">
				<tr v-for="provider in providers" :key="provider.surrogate_key">
					<th>{{provider.Centre}}</th>
					<th>{{provider.Total}}</th>
					<th>{{roundNumber((provider.Total * 100) / map_data.Total)}}</th>
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
	</div>
</template>

<script>
import { latLng} from "leaflet";
import { LMap, LTileLayer, LMarker, LGeoJson,LControl } from "vue2-leaflet";



export default {
	components: {
		LMap,
		LTileLayer,
		LGeoJson,
		LMarker,
		LControl
	},
	props:{
		filter_types:{
			type: Array,
			required: false
		},
	},
	data() {
		return {
			loading: false,
			zoom: 7,
			center: [-37.02100, 144.964600],
			fillColor: "#007467",
			url: 'https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/{z}/{x}/{y}?access_token='+ process.env.MIX_MAPBOX_TOKEN,
			attribution:'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
					'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			grades : [ {"value":"1000",
						"color": "#00453d"},
						{
						"value":"500",
						"color": "#005c52"
						},
						{
						"value":"200",
						"color": "#007467"
						},
						{
						"value":"100",
						"color": "#328f85"
						},
						{
						"value":"50",
						"color": "#4c9d94"
						},
						{
						"value":"20",
						"color": "#7fb9b3"
						},
						{
						"value":"10",
						"color": "#b2d5d1"
						},
						{
						"value":"1",
						"color": "#e5f1ef"
						},
						{
						"value":"No data",
						"color": "#8a8884"
						}],

		};

	},
	methods:{
		getColor(data){
			return 	data > 1000 ? '#00453d' :
					data > 500  ? '#005c52' :
					data > 200  ? '#007467' :
					data > 100  ? '#328f85' :
					data > 50   ? '#4c9d94' :
					data > 20   ? '#7fb9b3' :
					data > 10   ? '#b2d5d1' :
					data >= 1   ? '#e5f1ef' :
					'#8a8884';
		},
		zoomFeature(event){
			var self = this;
			self.$refs.map.mapObject.fitBounds(event.target.getBounds());
			self.addtoFilters(event.target.feature.properties);

		},
		addtoFilters(properties){
			var self = this;
			Array.prototype.forEach.call(self.filter_types, filter_type => {
				if(filter_type.name.localeCompare("Location") == 0){
					Array.prototype.forEach.call(filter_type.filters, filter => {
						if(filter.surrogate_key == properties.sa2){
							delete filter.children;
							if(!self.selected_filters.includes(filter)){
								//self.location_display = filter;
								self.selected_filters = filter;
							} else {
								self.$store.commit("rem_selected_filters",filter);
								//self.$store.commit("rem_location_display",filter);
							}
						}
					});
				}
			});
		},
		roundNumber(number){
			return number.toFixed(2);
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
					fillColor: self.getColor(feature.properties.client_count) ,//fillColor,
				};
			};
		},
		onEachFeatureFunction() {
			var self = this;
			return (feature, layer) => {
				if(!feature.properties.client_count){
					feature.properties.client_count = "No data";
				}
				layer.bindTooltip(
					"<div><span style='color:#971a4b;font-weight: bold'>" +
					feature.properties.sa2_name +
					"</span></div>" +
					feature.properties.client_count +  " clients",
					{ permanent: false, sticky: true }
				);
				layer.on('click', (event) =>{
					self.zoomFeature(event);
				}

				);
			};
		},
		map_data() {
			return this.$store.getters.map_data;
		},
		selected_filters:{
			get(){
				return this.$store.getters.selected_filters;
			},
			set(value){
				this.$store.commit("add_selected_filters",value);
			}
		},
		location_display:{
			get(){
				return this.$store.getters.location_display;
			},
			set(value){
				this.$store.commit("add_location_display",value);
			}
		},
		providers:{
			get(){
				return this.$store.getters.providers;
			},
			set(value){
				this.$store.commit("add_providers",value);
			}
		}
	},
	async created() {
/*
		this.loading = true;
		const response = await fetch("/geojson/sa2_2016(2).geojson")
		const data = await response.json();
		this.sa2 = data;
		this.loading = false;*/
	}
};
</script>
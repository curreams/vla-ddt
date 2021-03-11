<template>

	<div class="contaniner">
		<div>
			<span class="section-title">Legal Services</span>
			<br>
			<span class="bold">Clients serviced by location and service provider</span>
			<br>
			<span>The map displays the client count in {{location_text}} {{sp_text}}. The data is filtered by {{aol_text}} {{demographic_text}} {{date_text}}</span>
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
			<l-circle-marker v-for="vla_office in vla_offices"
							:key="vla_office.name + (Math.random()*1000)"
							:lat-lng="vla_office.latlong"
							:radius="2.5"
							:fillColor="vla_office_color"
							:color="vla_office_color">
								<l-tooltip :options="office_tooltip_options"><span class="ddt-footer-title">{{vla_office.name}}</span></l-tooltip>
			</l-circle-marker>
			<l-circle-marker v-for="clc_office in clc_offices"
							:key="clc_office.name + (Math.random()*1000)"
							:lat-lng="clc_office.latlong"
							:radius="2.5"
							:fillColor="clc_office_color"
							:color="clc_office_color">
								<l-tooltip :options="office_tooltip_options"><span class="ddt-footer-title">{{clc_office.name}}</span></l-tooltip>
			</l-circle-marker>
		</l-map>
	</div>
</template>

<script>
import { latLng} from "leaflet";
import { LMap, LTileLayer,LGeoJson,LControl,LCircleMarker,LTooltip } from "vue2-leaflet";



export default {
	components: {
		LMap,
		LTileLayer,
		LGeoJson,
		LCircleMarker,
		LControl,
		LTooltip
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
			vla_offices:[	{"name":"VLA Bairnsdale", "latlong":[-37.825946, 147.633532]},
							{"name":"VLA Ballarat", "latlong":[-37.557761, 143.856655]},
							{"name":"VLA Bendigo", "latlong":[-36.762194, 144.276833]},
							{"name":"VLA Broadmeadows", "latlong":[-37.683141, 144.919150]},
							{"name":"VLA Dandenong", "latlong":[-37.987569, 145.211529]},
							{"name":"VLA Frankston", "latlong":[-38.138764, 145.127393]},
							{"name":"VLA Geelong", "latlong":[-38.147741, 144.360782]},
							{"name":"VLA Horsham", "latlong":[-36.715822, 142.197422]},
							{"name":"VLA Melbourne", "latlong":[-37.815795, 144.956971]},
							{"name":"VLA Mildura", "latlong":[-34.192194, 142.153250]},
							{"name":"VLA Morwell", "latlong":[-38.238546, 146.399789]},
							{"name":"VLA Ringwood", "latlong":[-37.813822, 145.226988]},
							{"name":"VLA Shepparton", "latlong":[-36.380216, 145.399220]},
							{"name":"VLA Sunshine", "latlong":[-37.776610, 144.831649]},
							{"name":"VLA Warrnambool", "latlong":[-38.378606, 142.481005]},
						],
			office_tooltip_options:{ permanent: false, sticky: true },
			vla_office_color:"#971a4b",
			clc_offices:[	{"name":"Environmental Justice Australia", "latlong":[-37.7984863,144.9381732]},
							{"name":"Flemington & Kensington Legal Centre", "latlong":[-37.789148,144.928727]},
							{"name":"Murray Mallee Community Legal Service", "latlong":[-34.186926,142.161944]},
							{"name":"Disability Discrimination Legal Service", "latlong":[-37.8167385,144.9661317]},
							{"name":"Villamanta Disability Rights Legal Serv.", "latlong":[-38.150096,144.364697]},
							{"name":"Moreland Community Legal Centre Inc.", "latlong":[-37.754968,144.963868]},
							{"name":"Melbourne University Student Union Inc", "latlong":[-37.7963689,144.9611738]},
							{"name":"Wyndham Legal Service Inc", "latlong":[-37.899829,144.661708]},
							{"name":"Central Highlands Community Legal Centre", "latlong":[-37.562779,143.867966]},
							{"name":"Women's Legal Service Victoria", "latlong":[-37.8123888,144.956506]},
							{"name":"Peninsula Community Legal Centre Inc.", "latlong":[-37.929363,145.066847]},
							{"name":"Job Watch Inc", "latlong":[-37.805663,144.967272]},
							{"name":"Footscray Community Legal Centre", "latlong":[-37.799736,144.899734]},
							{"name":"Darebin Community Legal Centre Inc", "latlong":[-37.756319,145.001314]},
							{"name":"Moonee Valley Legal Service", "latlong":[-37.779653,144.919517]},
							{"name":"Broadmeadows Community Legal Service", "latlong":[-37.69067,144.926259]},
							{"name":"Mental Health Legal Centre Inc", "latlong":[-37.818404,144.962629]},
							{"name":"Barwon Community Legal Service", "latlong":[-38.136276,144.348817]},
							{"name":"Victorian Aboriginal Legal Service Co-op", "latlong":[-37.743764,145.002669]},
							{"name":"Moreland Community Legal Centre Inc.", "latlong":[-37.741435,144.970866]},
							{"name":"Western Suburbs Legal Service", "latlong":[-37.842536,144.884073]},
							{"name":"West Heidelberg Community Legal Service", "latlong":[-37.739503,145.0406019]},
							{"name":"Tenants Union Legal Service", "latlong":[-37.807518,144.98245]},
							{"name":"St Kilda Legal Service", "latlong":[-37.86874,144.989688]},
							{"name":"Springvale Monash Legal Service Inc", "latlong":[-37.946544,145.148434]},
							{"name":"Peninsula Community Legal Centre Inc.", "latlong":[-38.142682,145.121693]},
							{"name":"Fitzroy Legal Service", "latlong":[-37.798629,144.979089]},
							{"name":"Inner Melbourne Community Legal Inc", "latlong":[-37.8049821,144.949609]},
							{"name":"Monash Oakleigh Legal Service Inc", "latlong":[-37.912265,145.128092]},
							{"name":"Refugee And Immigration Legal Centre Inc", "latlong":[-37.8038679,144.9772131]},
							{"name":"Eastern Community Legal Centre Inc", "latlong":[-37.81966,145.126616]},
							{"name":"Brimbank Melton Community Legal Centre", "latlong":[-37.68423,144.580101]},
							{"name":"Casey Cardinia Community Legal Service", "latlong":[-37.996868,145.227691]},
							{"name":"Peninsula Community Legal Centre Inc.", "latlong":[-38.1119355,145.2829099]},
							{"name":"Brimbank Melton Community Legal Centre", "latlong":[-37.769462,144.772845]},
							{"name":"Brimbank Melton Community Legal Centre", "latlong":[-37.743613,144.796911]},
							{"name":"Consumer Action Law Centre", "latlong":[-37.8165557,144.9595833]},
							{"name":"Seniors Rights Victoria", "latlong":[-37.8157147,144.9639263]},
							{"name":"Loddon Campaspe Community Legal Centre", "latlong":[-36.761599,144.280271]},
							{"name":"Human Rights Law Resource Centre Ltd", "latlong":[-37.8139228,144.9562364]},
							{"name":"Asylum Seeker Resource Centre", "latlong":[-37.8101374,144.9518371]},
							{"name":"Whittlesea Community Connections", "latlong":[-37.6492727,145.0013851]},
							{"name":"Aboriginal Family Violence Prevention & Legal Service", "latlong":[-37.806372,144.986367]},
							{"name":"Hume Riverina Community Legal Service", "latlong":[-36.122487,146.8878851]},
							{"name":"Young People's Legal Rights Centre", "latlong":[-37.8199278,144.9573055]},
							{"name":"Hume Riverina Community Legal Service", "latlong":[-36.1217519,146.8862131]},
							{"name":"Victorian Aboriginal Legal Service Co-op", "latlong":[-37.828207,147.62342]},
							{"name":"Emma House Domestic Violence Services Inc.", "latlong":[-38.378318,142.478878]},
							{"name":"Southport Community Legal Service", "latlong":[-37.833401,144.95596]},
							{"name":"Aboriginal Family Violence Prevention & Legal Service", "latlong":[-34.187268,142.155165]},
							{"name":"Aboriginal Family Violence Prevention & Legal Service", "latlong":[-37.8262942,147.6286052]},
							{"name":"South West Community Legal Centre", "latlong":[-38.384498,142.482636]},
							{"name":"Aboriginal Family Violence Prevention & Legal Service", "latlong":[-38.381504,142.481763]},
							{"name":"Aed Legal Centre", "latlong":[-37.817917,144.965065]},
			],
			clc_office_color:"#0F9AD6",
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
		},
		location_text:{
			get(){
				return this.$store.getters.location_text;
			}
		},
		sp_text:{
			get(){
				return this.$store.getters.sp_text;
			}
		},
		aol_text:{
			get(){
				return this.$store.getters.aol_text;
			}
		},
		demographic_text:{
			get(){
				return this.$store.getters.demographic_text;
			}
		},
		date_text:{
			get(){
				return this.$store.getters.date_text;
			}
		},

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
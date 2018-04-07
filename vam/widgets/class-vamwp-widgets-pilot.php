<?php

/**
 * The pilot widgets. Defines pilot-specific widgets for presentation to an
 * authenticated pilot. Assumes pilot has logged into Wordpress and that
 * their CALLSIGN is their Wordpress username for use with logged in users.
 *
 * Widgets must be placed on a page which receives the pilot's VAM ID
 * in the "pilot_id" URL paramter or where the user is logged in to Wordpress.
 *
 * @link              https://github.com/likeablegeek/vamwp
 * @since             1.0.0
 * @package           VAMWwp
 */

 /*

 VAMwp 1.0.0-2.6.2 (https://github.com/likeablegeek/vamwp)
 WORDPRESS PLUGIN for VAM 2.6.2 (http://virtualairlinesmanager.net/)

 	By: Arman Danesh

 	License:

 	Copyright (c) 2018 Arman Danesh

 	Licensed under the Apache License, Version 2.0 (the "License");
 	you may not use this file except in compliance with the License.
 	You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

 	Unless required by applicable law or agreed to in writing, software
 	distributed under the License is distributed on an "AS IS" BASIS,
 	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 	See the License for the specific language governing permissions and
 	limitations under the License.

 */

 /**
 * Widgets defined in this file:
 *
 * VAM_Pilot_Pofile: Displays a pilot's general profile information
 * VAM_Widget_PilotFlights: Displays a list of flights by a pilot
 * VAM_Widget_PilotFlightsMap: Displays a map of flights by a pilot
 */

/**
* VAM_Pilot_Pofile: Displays a pilot's general profile information
*/
 class VAM_Pilot_Profile extends WP_Widget {

   public function get_pilot_data($callsign) {

 		$data = array();

 		$sql = "select gvausers.gvauser_id,gvausers.name as firstname,surname as lastname,gvausers.ivaovid,
 				gvausers.location,gvausers.vatsimid,gvausers.gva_hours,
 				gvausers.user_type_id,gvausers.hub_id,gvausers.rank_id,
 				user_types.user_type_id,user_type,hubs.hub_id,hubs.hub,
 				ranks.rank_id,ranks.rank,
 				a1.name as hub_name, a1.iso_country as hub_country,
 				a2.name as location_name, a2.iso_country as location_country
 				from gvausers,user_types,hubs,ranks,airports a1,airports a2
 				where callsign='" . $callsign . "'
 				and gvausers.user_type_id = user_types.user_type_id
 				and gvausers.hub_id = hubs.hub_id
 				and gvausers.rank_id = ranks.rank_id
 				and a1.ident = hubs.hub
 				and a2.ident = gvausers.location";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$id = $row["gvauser_id"];
 			$name = $row["firstname"] . " " . $row["lastname"];
 			$ivao = $row["ivaovid"];
 			$location = $row["location"];
 			$location_name = $row["location_name"];
 			$location_country = $row["location_country"];
 			$vatsim = $row["vatsimid"];
 			$hours = $row["gva_hours"];
 			$usertype = $row["user_type"];
 			$hub = $row["hub"];
      $hub_id = $row["hub_id"];
 			$hub_name = $row["hub_name"];
 			$hub_country = $row["hub_country"];
 			$rank = $row["rank"];
 		}

 		$data['id'] = $id;
 		$data['name'] = $name;
 		$data['ivao'] = $ivao;
 		$data['location'] = $location;
 		$data['location_name'] = $location_name;
 		$data['location_country'] = $location_country;
 		$data['vatsim'] = $vatsim;
 		$data['hours'] = $hours;
 		$data['usertype'] = $usertype;
 		$data['hub'] = $hub;
    $data['hub_id'] = $hub_id;
 		$data['hub_name'] = $hub_name;
 		$data['hub_country'] = $hub_country;
 		$data['rank'] = $rank;
 		$data['callsign'] = $callsign;

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_pilot_profile',
 			'description' => 'VAM Pilot Profile',
 		);
 		parent::__construct( 'vam_widget_pilot_profile', 'VAM Pilot Profile', $widget_ops );
 		$this->vam = new VAMWP_VAM();
    $this->callsign = (isset($_GET["pilot_id"])) ? $this->vam->get_pilot_callsign($_GET["pilot_id"]) : wp_get_current_user()->user_login;
    $this->pilot_data = $this->get_pilot_data($this->callsign);

 	}

 	/**
 	 * Outputs the content of the widget
 	 *
 	 * @param array $args
 	 * @param array $instance
 	 */
 	public function widget( $args, $instance ) {
 		// outputs the content of the widget
 		echo $args['before_widget'];
 		if (!empty($instance['title'])) {
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->callsign . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable row-border" id="vam-pilot-profile">
 			<thead>
 			<tr>
 				<th>' . __("Item", "vamwp") . '</th>
 				<th>' . __("Value", "vamwp") . '</th>
 			</tr>
 			</thead>
 			<tbody>
 			<tr>
 				<td class="vam_tdhead">' . __("Callsign", "vamwp") . '</td>
 				<td>' . $this->pilot_data['callsign'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Name", "vamwp") . '</td>
 				<td>' . $this->pilot_data['name'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("Rank", "vamwp") . '</td>
 				<td>' . $this->pilot_data['rank'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("Hub", "vamwp") . '</td>
 				<td>'  . $this->vam->get_departure_icon() . $this->vam->get_flag_icon($this->pilot_data['hub_country']) . '<a
 					href="' . $this->vam->get_vam_url('hub',$this->pilot_data['hub_id']) . '">' . $this->pilot_data['hub'] . '</a>
 					<span class="vam-airportname">' . str_replace("Airport","",$this->pilot_data["hub_name"]) . '</span></td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("Location", "vamwp") . '</td>
 				<td>'  . $this->vam->get_departure_icon() . $this->vam->get_flag_icon($this->pilot_data['location_country']) . '<a
 					href="' . $this->vam->get_vam_url('airport_info',$this->pilot_data['location']) . '">' . $this->pilot_data['location'] . '</a>
 					<span class="vam-airportname">' . str_replace("Airport","",$this->pilot_data["location_name"]) . '</span></td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("Hours", "vamwp") . '</td>
 				<td>' . $this->pilot_data['hours'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("IVAO", "vamwp") . '</td>
 				<td>' . (($this->pilot_data['ivao'] > 0) ? $this->pilot_data['ivao'] : 'N/A') . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("VATSIM", "vamwp") . '</td>
 				<td>' . (($this->pilot_data['vatsim'] > 0) ? $this->pilot_data['vatsim'] : 'N/A') . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead">' . __("User type", "vamwp") . '</td>
 				<td>' . $this->pilot_data['usertype'] . '</td>
 			</tr>
 			</tbody>
 		</table>');
 		echo $args['after_widget'];
 	}

 	/**
 	 * Outputs the options form on admin
 	 *
 	 * @param array $instance The widget options
 	 */
 	public function form( $instance ) {
 		// outputs the options form on admin
 		$title = ! empty($instance['title']) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
 		?>
 		<p>
 			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">
 				<?php esc_attr_e( 'Title:', 'text_domain' ); ?>
 			</label>
 			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>"
 				name="<?php echo esc_attr( $this->get_field_name('title') ); ?>"
 				type="text"
 				value="<?php echo esc_attr( $title ); ?>">
 		</p>
 		<?php
 	}

 	/**
 	 * Processing widget options on save
 	 *
 	 * @param array $new_instance The new options
 	 * @param array $old_instance The previous options
 	 *
 	 * @return array
 	 */
 	public function update( $new_instance, $old_instance ) {
 		// processes widget options to be saved
 		$instance = array();
 		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
 		return $instance;
 	}
 }
 add_action( 'widgets_init', function(){
 	register_widget( 'VAM_Pilot_Profile' );
 });

/**
* VAM_Widget_PilotFlights: Displays a list of flights by a pilot
*/
 class VAM_Widget_PilotFlights extends WP_Widget {

   public function get_pilot_flights($callsign) {

 		$data = array();
 		$count = 0;

 		$sql = "select a1.iso_country as dep_country, a2.iso_country as arr_country ,
 			REPLACE(a1.name,'Airport','') as dep_name,REPLACE(a2.name,'Airport','') as arr_name,
 			CreatedOn as date_int,pirepfsfk_id as id,'' as comment,validated as status,
 			pirepfsfk_id as flight, SUBSTRING(OriginAirport,1,4) departure,
 			SUBSTRING(DestinationAirport,1,4) arrival ,
 			DATE_FORMAT(CreatedOn,'" . $this->vam->va_date_format . "') as date_string,
 			DistanceFlight as distance, FlightTime as duration, charter , 'keeper' as type ,
 			flight as flight_regular
           	from pirepfsfk , airports a1, airports a2, gvausers
           	where a1.ident=SUBSTRING(OriginAirport,1,4) and
           	a2.ident=SUBSTRING(DestinationAirport,1,4)
           	and pirepfsfk.gvauser_id = gvausers.gvauser_id
           	and gvausers.callsign = '" . $callsign . "'
           	UNION
 			SELECT a1.iso_country as dep_country, a2.iso_country as arr_country ,
 			REPLACE(a1.name,'Airport','') as dep_name,REPLACE(a2.name,'Airport','') as arr_name,
 			date as date_int,report_id as id,'' as comment , validated as status,
 			report_id as flight , origin_id as departure, destination_id as arrival,
 			DATE_FORMAT(date,'" . $this->vam->va_date_format . "') as date_string, distance,
 			(HOUR(duration)*60 + minute(duration))/60 as duration, charter, 'Fsacars' as type,
 			flight as flight_regular
           	from reports , airports a1, airports a2, gvausers
           	where a1.ident=origin_id and a2.ident=destination_id
           	and reports.pilot_id = gvausers.gvauser_id
           	and gvausers.callsign = '" . $callsign . "'
 			UNION
 			select a1.iso_country as dep_country, a2.iso_country as arr_country ,
 			REPLACE(a1.name,'Airport','') as dep_name,REPLACE(a2.name,'Airport','') as arr_name,
 			date as date_int,pirep_id as id,comment,valid as status,pirep_id as flight,
 			from_airport departure, to_airport arrival ,
 			DATE_FORMAT(date,'" . $this->vam->va_date_format . "')
 			as date_string,distance,duration,charter, 'pirep' as type ,flight as flight_regular
 			from pireps  , airports a1, airports a2, gvausers
 			where a1.ident=from_airport and a2.ident=to_airport
           	and pireps.gvauser_id = gvausers.gvauser_id
           	and gvausers.callsign = '" . $callsign . "'
 			UNION
 			SELECT a1.iso_country as dep_country, a2.iso_country as arr_country ,
 			REPLACE(a1.name,'Airport','') as dep_name,REPLACE(a2.name,'Airport','') as arr_name,
 			flight_date as date_int, flightid as id,'' as comment , validated as status,
 			flightid as flight, departure, arrival ,
 			DATE_FORMAT(flight_date,'" . $this->vam->va_date_format . "') as date_string,
 			distance, flight_duration as duration, charter, 'VAMACARS' as type, flight as flight_regular
 			from vampireps , airports a1, airports a2, gvausers
 			where a1.ident=departure and a2.ident=arrival
           	and vampireps.gvauser_id = gvausers.gvauser_id
           	and gvausers.callsign = '" . $callsign . "'
 			order by date_int desc, id desc";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['departure'] = $row['departure'];
 			$data[$count]['arrival'] = $row['arrival'];
 			$data[$count]['dep_name'] = $row['dep_name'];
 			$data[$count]['arr_name'] = $row['arr_name'];
 			$data[$count]['dep_country'] = $row['dep_country'];
 			$data[$count]['arr_country'] = $row['arr_country'];
 			$data[$count]['duration'] = $row['duration'];
 			$data[$count]['date_string'] = $row['date_string'];
 			$data[$count]['date'] = $row['date_int'];
 			$data[$count]['distance'] = $row['distance'];
 			$data[$count]['type'] = $row['type'];
 			$data[$count]['flight_regular'] = $row['flight_regular'];
 			$data[$count]['flight'] = $row['flight'];
 			$data[$count]['status'] = $row['status'];
 			$data[$count]['charter'] = $row['charter'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_pilotflights',
 			'description' => 'VAM Pilot Flights',
 		);
 		parent::__construct( 'vam_widget_pilotflights', 'VAM Pilot Flights', $widget_ops );
 		$this->vam = new VAMWP_VAM();
    $this->callsign = (isset($_GET["pilot_id"])) ? $this->vam->get_pilot_callsign($_GET["pilot_id"]) : wp_get_current_user()->user_login;
 		$this->flight_data = $this->get_pilot_flights($this->callsign);
 	}

 	/**
 	 * Outputs the content of the widget
 	 *
 	 * @param array $args
 	 * @param array $instance
 	 */
 	public function widget( $args, $instance ) {
 		// outputs the content of the widget
 		echo $args['before_widget'];
 		if (!empty($instance['title'])) {
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->callsign . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-latestflights">
 			<thead>
 				<tr>
 					<th>' . __("Date", "vamwp") . '</th>
 					<th>' . __("Flight", "vamwp") . '</th>
 					<th>' . __("Departure", "vamwp") . '</th>
 					<th>' . __("Arrival", "vamwp") . '</th>
 					<th>' . __("Duration", "vamwp") . '</th>
 					<th>' . __("Distance", "vamwp") . '</th>
 					<th>' . __("Validation", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->flight_data as $flight) {

 			$status = "remove";
 			$status_color = "DC143C";
 			if ($flight['status'] == 0) { $status = "time"; $status_color = "C36900"; }
 			if ($flight['status'] == 1) { $status = "ok"; $status_color = "228B22"; }

 			echo('<tr>
 				<td>' . $flight['date_string'] . '</td>
 				<td>' . $flight['flight_regular'] . '</td>
 				<td>' . $this->vam->get_departure_icon() . $this->vam->get_flag_icon($flight['dep_country']) . '<a
 						href="' . $this->vam->get_vam_url('airport_info',$flight['departure']) . '">' . $flight['departure'] . '</a></td>
 				<td>' . $this->vam->get_arrival_icon() . $this->vam->get_flag_icon($flight['arr_country']) . '<a
 						href="' . $this->vam->get_vam_url('airport_info',$flight['arrival']) . '">' . $flight['arrival'] . '</a></td>
 				<td><span class="fa fa-clock-o fa-fw"></span>' . $this->vam->convertTime(round($flight['duration'], 2), $this->vam->va_time_format) . '</td>
 				<td><span class="fa fa-expand fa-fw"></span>' . $flight['distance'] . '</td>
 				<td><font color="#' . $status_color . '"><span class="glyphicon glyphicon-' . $status . ' fa-lg"></span></font></td>
 			</tr>');

 		}

 		echo ( '</tbody>
 		</table>');
 		echo $args['after_widget'];
 	}

 	/**
 	 * Outputs the options form on admin
 	 *
 	 * @param array $instance The widget options
 	 */
 	public function form( $instance ) {
 		// outputs the options form on admin
 		$title = ! empty($instance['title']) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
 		?>
 		<p>
 			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">
 				<?php esc_attr_e( 'Title:', 'text_domain' ); ?>
 			</label>
 			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>"
 				name="<?php echo esc_attr( $this->get_field_name('title') ); ?>"
 				type="text"
 				value="<?php echo esc_attr( $title ); ?>">
 		</p>
 		<?php
 	}

 	/**
 	 * Processing widget options on save
 	 *
 	 * @param array $new_instance The new options
 	 * @param array $old_instance The previous options
 	 *
 	 * @return array
 	 */
 	public function update( $new_instance, $old_instance ) {
 		// processes widget options to be saved
 		$instance = array();
 		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
 		return $instance;
 	}
 }
 add_action( 'widgets_init', function(){
 	register_widget( 'VAM_Widget_PilotFlights' );
 });

/**
* VAM_Widget_PilotFlightsMap: Displays a map of flights by a pilot
*/
 class VAM_Widget_PilotFlightsMap extends WP_Widget {

   public function get_pilot_flights_map($callsign) {

 		$data = array();
 		$count = 0;

 		$sql = "select from_airport departure, to_airport arrival, date
 					from pireps, gvausers
 					where valid<>3 and valid<>2
 		          	and pireps.gvauser_id = gvausers.gvauser_id
         		  	and gvausers.callsign = '" . $callsign . "'
     				UNION
    					select  SUBSTRING(OriginAirport,1,4) departure,
    					SUBSTRING(DestinationAirport,1,4) arrival ,
    					CreatedOn as date
    					from pirepfsfk, gvausers
    					where pirepfsfk.gvauser_id = gvausers.gvauser_id
         		  	and gvausers.callsign = '" . $callsign . "'
     				UNION
    					SELECT origin_id as departure, destination_id as arrival, date
    					from reports, gvausers
    					where reports.pilot_id = gvausers.gvauser_id
         		  	and gvausers.callsign = '" . $callsign . "'
 					UNION
     				SELECT departure, arrival, flight_date as date
     				from vampireps, gvausers
 		          	where vampireps.gvauser_id = gvausers.gvauser_id
         		  	and gvausers.callsign = '" . $callsign . "'
     				order by date desc limit 10";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		unset($flights);
 		$flights = array();

 		while ($row = $result->fetch_assoc()) {
 			$flights[$count] = $row['departure'];
 			$count++;
 			$flights[$count] = $row['arrival'];
 			$count++;
 		}

 		$count = 0;
 		foreach($flights as $flight) {
 			$sql_map = "select latitude_deg, longitude_deg ,ident ,
 							airports.name as airport_name
 							from airports where ident='" . $flight . "'";
 			if (!$result = $this->vam->db->query($sql_map)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$data[$count] = array ($row["latitude_deg"],  $row["longitude_deg"] ,  $row["ident"],  $row["airport_name"] ) ;
 				$count++;
 			}
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_pilotflightsmap',
 			'description' => 'VAM Pilot Flights Map',
 		);
 		parent::__construct( 'vam_widget_pilotflightsmap', 'VAM Pilot Flights Map', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->wp_user = wp_get_current_user();
    $this->callsign = (isset($_GET["pilot_id"])) ? $this->vam->get_pilot_callsign($_GET["pilot_id"]) : wp_get_current_user()->user_login;
    $this->flight_data = $this->get_pilot_flights_map($this->callsign);

    if ( count($this->flight_data) > 0 ) {

   		add_action('wp_head', function() {
        echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . get_option("mt_vam_googlemaps_api_key") . '&callback=init_map" type="text/javascript"></script>';
   			echo '<script type="text/javascript">
            var a;
   					function init_map() {
   						var locations = ' . json_encode($this->flight_data) . ';
   						var var_location = new google.maps.LatLng(' . $this->flight_data[0][0] . ',' . $this->flight_data[0][1] . ');
   						var var_mapoptions = {
   							center: var_location,
   							zoom: 5,
   							styles: [{featureType:"road",elementType:"geometry",stylers:[{lightness:100},{visibility:"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#C6E2FF",}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#C5E3BF"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#D1D1B8"}]}]
   						};
   						var var_map = new google.maps.Map(document.getElementById("map-container"),var_mapoptions);
   					var k=0;
   					while (k<20) {
   						dep = new google.maps.LatLng(locations[k][0], locations[k][1]);
   						arr = new google.maps.LatLng(locations[k+1][0], locations[k+1][1]);
   						var icon_red = "/images/airport_runway_red.png";
   						//var icon_green = "/images/airport_runway_green.png";
   						var icon_green = "/images/icons/ic_location_on_black_24dp_1x.png";
   						var marker_dep = new google.maps.Marker({
   							position: dep,
   							icon: icon_green
   						});
   						var marker_arr = new google.maps.Marker({
   							position: arr,
   							icon: icon_green
   						});
   						marker_dep.setMap(var_map);
   						marker_arr.setMap(var_map);
   						var var_marker = new google.maps.Polyline({
   							path: [dep, arr],
   							geodesic: true,
   							strokeColor: "#FF0000",
   							strokeOpacity: 1.0,
   							strokeWeight: 2
   						});
   						var_marker.setMap(var_map);
   						var marker_dep = new google.maps.Marker({
   							position: dep,
   							icon: icon_green
   						});
   						var marker_arr = new google.maps.Marker({
   							position: arr,
   							icon: icon_green
   						});
   						marker_dep.setMap(var_map);
   						marker_arr.setMap(var_map);
   						k=k+2;
   					}
   				}
   				</script>';
   			echo '<style>
   						#map-outer {
   							padding: 0px;
   							border: 0px solid #CCC;
   							margin-bottom: 0px;
   							background-color:#FFFFF;
   							width: 710px; }
   						#map-container { height: 500px }
   						@media all and (max-width: 991px) {
   							#map-outer  { height: 650px }
   						}
   				</style>';
   		});

   		add_action('wp_footer', function() {
   			echo '<script type="text/javascript">
   				google.maps.event.addDomListener(window, "load", init_map);
   			</script>';
   		});

    }

 	}

 	/**
 	 * Outputs the content of the widget
 	 *
 	 * @param array $args
 	 * @param array $instance
 	 */
 	public function widget( $args, $instance ) {
 		// outputs the content of the widget
 		echo $args['before_widget'];
 		if (!empty($instance['title'])) {
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->callsign . $args['after_title'];
 		}

 		echo ( '<div class="container">
 					<div class="row">
 						<div id="map-outer" class="col-md-11">
 							<div id="map-container" class="col-md-12"></div>
 						</div>
 					</div>
 				</div>');

 		echo $args['after_widget'];
 	}

 	/**
 	 * Outputs the options form on admin
 	 *
 	 * @param array $instance The widget options
 	 */
 	public function form( $instance ) {
 		// outputs the options form on admin
 		$title = ! empty($instance['title']) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
 		?>
 		<p>
 			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">
 				<?php esc_attr_e( 'Title:', 'text_domain' ); ?>
 			</label>
 			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>"
 				name="<?php echo esc_attr( $this->get_field_name('title') ); ?>"
 				type="text"
 				value="<?php echo esc_attr( $title ); ?>">
 		</p>
 		<?php
 	}

 	/**
 	 * Processing widget options on save
 	 *
 	 * @param array $new_instance The new options
 	 * @param array $old_instance The previous options
 	 *
 	 * @return array
 	 */
 	public function update( $new_instance, $old_instance ) {
 		// processes widget options to be saved
 		$instance = array();
 		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
 		return $instance;
 	}
 }
 add_action( 'widgets_init', function(){
 	register_widget( 'VAM_Widget_PilotFlightsMap' );
 });
 ?>

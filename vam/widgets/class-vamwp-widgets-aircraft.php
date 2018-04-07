<?php

/**
 * The aircraft widgets. Defines aircraft-specific widgets.
 *
 * Widgets must be placed on a page which receives the aircraft registry id
 * in the "registry_id" URL paramter.
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
 * VAM_Widget_Aircraft_Flights: Displays a list of all flights by an aircraft
 * VAM_Widget_Aircraft_Maintenance: Displays maintenance history of an aircraft
 * VAM_Widget_Aircraft_Details: Displays general aircraft information
 */

/**
* VAM_Widget_Aircraft_Flights: Displays a list of all flights by an aircraft
*/
 class VAM_Widget_Aircraft_Flights extends WP_Widget {

   public function get_aircraft_flights($aircraft) {

 		$data = array();
 		$count = 0;

 		$sql = "select a1.name as dep_name, a2.name as arr_name, a1.iso_country as dep_country,
 				a2.iso_country as arr_country,gu.gvauser_id,ft.fuel fuel,
 				DATE_FORMAT(date,'" . $this->vam->va_date_format . "') as date,
 				gu.name,gu.surname,callsign,flight,ft.departure,ft.arrival,distance from
 				fleets f inner join regular_flights_tracks ft on f.fleet_id=ft.fleet_id
 				inner join gvausers gu on gu.gvauser_id = ft.gvauser_id
 				left outer join routes r on ft.route_id=r.route_id
 				inner join airports a1 on (a1.ident=ft.departure)
 				inner join airports a2 on (a2.ident=ft.arrival)
 				where registry='$aircraft'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['dep_name'] = $row['dep_name'];
 			$data[$count]['arr_name'] = $row['arr_name'];
 			$data[$count]['dep_country'] = $row['dep_country'];
 			$data[$count]['arr_country'] = $row['arr_country'];
 			$data[$count]['gvauser_id'] = $row['gvauser_id'];
 			$data[$count]['date'] = $row['date'];
 			$data[$count]['fuel'] = $row['fuel'];
 			$data[$count]['name'] = $row['name'];
 			$data[$count]['surname'] = $row['surname'];
 			$data[$count]['callsign'] = $row['callsign'];
 			$data[$count]['flight'] = $row['flight'];
 			$data[$count]['departure'] = $row['departure'];
 			$data[$count]['arrival'] = $row['arrival'];
 			$data[$count]['distance'] = $row['distance'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_aircraft_flights',
 			'description' => 'VAM Aircraft Flights',
 		);
 		parent::__construct( 'vam_widget_aircraft_flights', 'VAM Aircraft Flights', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->aircraft = (isset($_GET["registry_id"])) ? $_GET["registry_id"] : "";
 		$this->flights_data = $this->get_aircraft_flights($this->aircraft);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->aircraft . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-aircraft-flights">
 			<thead>
 				<tr>
        <th>' . __("Date", "vamwp") . '</th>
        <th>' . __("Pilot", "vamwp") . '</th>
        <th>' . __("Callsign", "vamwp") . '</th>
        <th>' . __("Flight", "vamwp") . '</th>
        <th>' . __("Departure", "vamwp") . '</th>
        <th>' . __("Arrival", "vamwp") . '</th>
        <th>' . __("Distance", "vamwp") . '</th>
        <th>' . __("Fuel", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->flights_data as $flight_details) {

 			echo('<tr>
 				<td>' . $flight_details['date'] . '</td>
 				<td>' . $flight_details['name'] . ' ' . $flight_details['surname'] . '</td>
 				<td><a href="' . $this->vam->get_vam_url("pilot_details", $flight_details["gvauser_id"]) . '">' .
 					$flight_details['callsign'] . '</a></td>
 				<td>' . $flight_details['flight'] . '</td>
 				<td>' . $this->vam->get_departure_icon() . $this->vam->get_flag_icon($flight_details['dep_country']) . '<a
 						href="' . $this->vam->get_vam_url('airport_info',$flight_details['departure']) . '">' . $flight_details['departure'] . '</a></td>
 				<td>' . $this->vam->get_arrival_icon() . $this->vam->get_flag_icon($flight_details['arr_country']) . '<a
 						href="' . $this->vam->get_vam_url('airport_info',$flight_details['arrival']) . '">' . $flight_details['arrival'] . '</a></td>
 				<td>' . $flight_details['distance'] . '</td>
 				<td>' . $flight_details['fuel'] . '</td>
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
 	register_widget( 'VAM_Widget_Aircraft_Flights' );
 });

/**
* VAM_Widget_Aircraft_Maintenance: Displays maintenance history of an aircraft
*/
 class VAM_Widget_Aircraft_Maintenance extends WP_Widget {

   public function get_aircraft_maintenance($aircraft) {

 		$data = array();
 		$count = 0;

 		$sql = "select DATE_FORMAT(date_in,'" . $this->vam->va_date_format . "') as datein,
 				DATE_FORMAT(date_out,'" . $this->vam->va_date_format . "') as dateout,
 				name, surname, reason
 				from hangar h inner join gvausers gu on
 				(h.gvauser_id=gu.gvauser_id) where registry='$aircraft'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['datein'] = $row['datein'];
 			$data[$count]['dateout'] = $row['dateout'];
 			$data[$count]['name'] = $row['name'];
 			$data[$count]['surname'] = $row['surname'];
 			$data[$count]['reason'] = $row['reason'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_aircraft_maintenance',
 			'description' => 'VAM Aircraft Maintenance',
 		);
 		parent::__construct( 'vam_widget_aircraft_maintenance', 'VAM Aircraft Maintenance', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->aircraft = (isset($_GET["registry_id"])) ? $_GET["registry_id"] : "";
 		$this->maint_data = $this->get_aircraft_maintenance($this->aircraft);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->aircraft . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-aircraft-maintenance">
 			<thead>
 				<tr>
        <th>' . __("Hangar Entry Date", "vamwp") . '</th>
        <th>' . __("Hangar Exit Date", "vamwp") . '</th>
        <th>' . __("Last Pilot", "vamwp") . '</th>
        <th>' . __("Reason", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->maint_data as $maint_details) {

 			echo('<tr>
 				<td>' . $maint_details['datein'] . '</td>
 				<td>' . $maint_details['dateout'] . '</td>
 				<td>' . $maint_details['name'] . ' ' . $maint_details['surname'] . '</td>
 				<td>' . $maint_details['reason'] . '</td>
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
 	register_widget( 'VAM_Widget_Aircraft_Maintenance' );
 });

/**
* VAM_Widget_Aircraft_Details: Displays general aircraft information
*/
 class VAM_Widget_Aircraft_Details extends WP_Widget {

   public function get_aircraft_details($aircraft) {

 		$data = array();
 		$count = 0;

 		$sql = "select hub,maximum_range,ft.image_url,booked,status, hours, crew_members ,
 				service_ceiling,cruising_speed,mtow,mlw,mzfw,aircraft_length, pax,
 				cargo_capacity,plane_description, a.iso_country location_iso2,
 				a2.iso_country hub_iso2, a.name airport ,a2.name hub_airport ,
 				f.name aircraft_name, location, hub_base, f.hub_id
 				from fleets f
 				inner join hubs hu on hu.hub_id= f.hub_id
 				inner join airports a on a.ident=f.location
 				inner join airports a2 on a2.ident=hub
 				inner join fleettypes ft on f.fleettype_id=ft.fleettype_id
 				where registry='$aircraft'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['hub'] = $row['hub'];
 			$data[$count]['maximum_range'] = $row['maximum_range'];
 			$data[$count]['image_url'] = $row['image_url'];
 			$data[$count]['booked'] = $row['booked'];
 			$data[$count]['status'] = $row['status'];
 			$data[$count]['hours'] = $row['hours'];
 			$data[$count]['crew_members'] = $row['crew_members'];
 			$data[$count]['service_ceiling'] = $row['sevice_ceiling'];
 			$data[$count]['cruising_speed'] = $row['cruising_speed'];
 			$data[$count]['mtow'] = $row['mtow'];
 			$data[$count]['mlw'] = $row['mlw'];
 			$data[$count]['mzfw'] = $row['mzfw'];
 			$data[$count]['aircraft_length'] = $row['aircraft_length'];
 			$data[$count]['pax'] = $row['pax'];
 			$data[$count]['cargo_capacity'] = $row['cargo_capacity'];
 			$data[$count]['plane_description'] = $row['plane_description'];
 			$data[$count]['location_iso2'] = $row['location_iso2'];
      $data[$count]['hub_iso2'] = $row['hub_iso2'];
      $data[$count]['hub_id'] = $row['hub_id'];
 			$data[$count]['airport'] = $row['airport'];
 			$data[$count]['hub_airport'] = $row['hub_airport'];
 			$data[$count]['aircraft_name'] = $row['aircraft_name'];
 			$data[$count]['location'] = $row['location'];
 			$data[$count]['hub_base'] = $row['hub_base'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_aircraft_details',
 			'description' => 'VAM Aircraft Details',
 		);
 		parent::__construct( 'vam_widget_aircraft_details', 'VAM Aircraft Details', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->aircraft = (isset($_GET["registry_id"])) ? $_GET["registry_id"] : "";
 		$this->plane_data = $this->get_aircraft_details($this->aircraft);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->aircraft . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable row-border" id="vam-aircraft-details">
 			<thead>
 			<tr>
 				<th>' . __("Item", "vamwp") . '</th>
 				<th>' . __("Value", "vamwp") . '</th>
 			</tr>
 			</thead>
 			<tbody>
 			<tr>
 				<td class="vam_tdhead" colspan=2><img width="100%" src="' . $this->plane_data[0]['image_url'] . '"></td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Name", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['name'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Location", "vamwp") . '</td>
 				<td>' . $this->vam->get_flag_icon($this->plane_data[0]['location_iso2']) . '<a href="' . $this->vam->get_vam_url('airport_info',$this->plane_data[0]['location']) . '">' .$this->plane_data[0]['location'] . '</a><span
 					class="vam-airportname">' . str_replace("Airport","",$this->plane_data[0]["airport"]) . '</span></td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Hub", "vamwp") . '</td>
 				<td>' . $this->vam->get_flag_icon($this->plane_data[0]['hub_iso2']) . '<a href="' . $this->vam->get_vam_url('hub',$this->plane_data[0]['hub_id']) . '">' .$this->plane_data[0]['hub'] . '</a><span
 					class="vam-airportname">' . str_replace("Airport","",$this->plane_data[0]["hub_airport"]) . '</span></td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Type", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['plane_description'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Hours", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['hours'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Booked", "vamwp") . '</td>
 				<td>' . (($this->plane_data[0]['name'] == 1) ? __("Booked", "vamwp") : __("Available", "vamwp")) . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead" id="vam_aircraft_status">' . __("Status", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['status'] . '%</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Number of PAX", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['pax'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Maximum Range", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['maximum_range'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Cargo Capacity", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['cargo_capacity'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Aircraft Length", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['aircraft_length'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Maximum Zero-fuel Weight (MZFW)", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['mzfw'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Maximum Landing Weight (MLW)", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['mlw'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Maximum Takeoff Weight (MTOW)", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['mtow'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Cruising Speed", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['cruising_speed'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Service Ceiling", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['service_ceiling'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead">' . __("Crew Members", "vamwp") . '</td>
 				<td>' . $this->plane_data[0]['crew_members'] . '</td>
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
 	register_widget( 'VAM_Widget_Aircraft_Details' );
 });

?>

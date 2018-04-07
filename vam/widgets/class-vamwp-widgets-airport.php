<?php

/**
 * The airport widgets. Defines airport-specific widgets.
 *
 * Widgets must be placed on a page which receives the airport ICAO in the
 * "airport" URL parameter.
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
 * VAM_Widget_Airport_Metar: Displays current METAR information for airport
 * VAM_Widget_Airport_Map: Displays map of airport via Google Maps
 * VAM_Widget_Airport_Runways: Displays list of runways for an airport
 * VAM_Widget_Airport_Frequencies: Displays list of frequencies for an airport
 * VAM_Widget_Airport_Navaids: Displays list of navigational aids for an airport
 */

/**
* VAM_Widget_Airport_Metar: Displays current METAR information for airport
*/
 class VAM_Widget_Airport_Metar extends WP_Widget {

   public function get_metar($location) {

 		$url ="http://aviationweather.gov/adds/metars/?station_ids=".$location."&std_trans=translated&chk_metars=on&hoursStr=past+1+hours&chk_tafs=on&submitmet=Submit";
 		$fileName = $url;

 		$metar = '';

 		$fileData = @file($fileName) or die('METAR not available');

 		if ($fileData != false) {
 			list($i , $date) = each($fileData);
 			$utc = strtotime(trim($date));
 			$time = date("D, F jS Y g:i A" , $utc);
 			while (list($i , $line) = each($fileData)) {
 				$metar .= ' ' . trim($line);
 			}
 			$metar = trim(str_replace('  ' , ' ' , $metar));
 			$metar = str_replace("Aviation Digital Data Service (ADDS)","",$metar);
 			$metar = str_replace("Output produced by METARs form","",$metar);
 			$metar = str_replace("found at","Data provider",$metar);
 		}

 		return $metar;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_airport_metar',
 			'description' => 'VAM Airport Metar',
 		);
 		parent::__construct( 'vam_widget_airport_metar', 'VAM Airport Metar', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->airport = (isset($_GET["airport"])) ? $_GET["airport"] : "";
 		$this->airport_metar = "";
 		if ($this->airport != "") {
 			$this->airport_metar = $this->get_metar($this->airport);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->airport . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo $this->airport_metar;


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
 	register_widget( 'VAM_Widget_Airport_Metar' );
 });

/**
* VAM_Widget_Airport_Map: Displays map of airport via Google Maps
*/
 class VAM_Widget_Airport_Map extends WP_Widget {

   public function get_airport_map_data($airport) {

 		$data = array();
 		$count = 0;

 		$sql = "select latitude_deg, longitude_deg ,ident ,
              airports.name as airport_name
              from airports where ident='$airport'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array ($row["latitude_deg"],  $row["longitude_deg"] ,  $row["ident"],  $row["airport_name"] );
 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_airport_map',
 			'description' => 'VAM Airport Map',
 		);
 		parent::__construct( 'vam_widget_airport_map', 'VAM Airport Map', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->airport = (isset($_GET["airport"])) ? $_GET["airport"] : "";
 		if ($this->airport != "") {
      $this->airport_map_data = $this->get_airport_map_data($this->airport);

      add_action('wp_head', function() {
        echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . get_option("mt_vam_googlemaps_api_key") . '&callback=init_map" type="text/javascript"></script>';
   			echo '<script type="text/javascript">
            var c;
   					function init_map() {
   						var locations = ' . json_encode($this->airport_map_data) . ';
   						var var_location = new google.maps.LatLng(' . $this->airport_map_data[0][0] . ',' . $this->airport_map_data[0][1] . ');
              var var_mapoptions = {
          			center: var_location,
          			zoom: 14,
          			mapTypeId: google.maps.MapTypeId.HYBRID ,
          			styles: [{featureType:"road",elementType:"geometry",stylers:[{lightness:100},{visibility:"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#C6E2FF",}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#C5E3BF"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#D1D1B8"}]}]
          		};
          		var var_map = new google.maps.Map(document.getElementById("map-container"),
          			var_mapoptions);
          	}
   				</script>';
   			echo '<style>
              	#map-outer {
              		padding: 0px;
              		border: 0px solid #CCC;
              		margin-bottom: 0px;
              		background-color:#FFFFF }
                  #map-outer-container { width: 100%; }
              	#map-container { height: 500px; width: 100%; }
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->airport . $args['after_title'];
 		}

    echo ( '<div class="container" id="map-outer-container">
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
 	register_widget( 'VAM_Widget_Airport_Map' );
 });

/**
* VAM_Widget_Airport_Runways: Displays list of runways for an airport
*/
 class VAM_Widget_Airport_Runways extends WP_Widget {

   public function get_runway_info($airport) {

 		$data = array();
 		$count = 0;

 		$sql = "select * from runways where airport_ident = '$airport'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['le_ident'] = $row['le_ident'];
 			$data[$count]['he_ident'] = $row['he_ident'];
 			$data[$count]['length_ft'] = $row['length_ft'];
 			$data[$count]['width_ft'] = $row['width_ft'];
 			$data[$count]['le_elevation_ft'] = $row['le_elevation_ft'];
 			$data[$count]['le_displaced_threshold_ft'] = $row['le_displaced_threshold_ft'];
 			$data[$count]['le_heading_degT'] = $row['le_heading_degT'];
 			$data[$count]['he_elevation_ft'] = $row['he_elevation_ft'];
 			$data[$count]['he_displaced_threshold_ft'] = $row['he_displaced_threshold_ft'];
 			$data[$count]['he_heading_degT'] = $row['he_heading_degT'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_airport_runways',
 			'description' => 'VAM Airport Runways',
 		);
 		parent::__construct( 'vam_widget_airport_runways', 'VAM Airport Runways', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->airport = (isset($_GET["airport"])) ? $_GET["airport"] : "";
 		$this->runway_data = $this->get_runway_info($this->airport);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->airport . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-runway-info">
 			<thead>
 				<tr>
        <th>' . __("Runway", "vamwp") . '</th>
        <th>' . __("Length", "vamwp") . '</th>
        <th>' . __("Width", "vamwp") . '</th>
        <th>' . __("Elevation", "vamwp") . '</th>
        <th>' . __("Displaced Threshold", "vamwp") . '</th>
        <th>' . __("Heading", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->runway_data as $runway_details) {

 			echo('<tr>
 				<td>' . $runway_details['le_ident'] . '</td>
 				<td>' . $runway_details['length_ft'] . ' ft</td>
 				<td>' . $runway_details['width_ft'] . ' ft</td>
 				<td>' . $runway_details['le_elevation_ft'] . ' ft</td>
 				<td>' . $runway_details['le_displaced_threshold_ft'] . ' ft</td>
 				<td>' . number_format($runway_details['le_heading_degT'], "0") . '</td>
 			</tr>');

 			echo('<tr>
 				<td>' . $runway_details['he_ident'] . '</td>
 				<td>' . $runway_details['length_ft'] . ' ft</td>
 				<td>' . $runway_details['width_ft'] . ' ft</td>
 				<td>' . $runway_details['he_elevation_ft'] . ' ft</td>
 				<td>' . $runway_details['he_displaced_threshold_ft'] . ' ft</td>
 				<td>' . number_format($runway_details['he_heading_degT'], "0") . '</td>
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
 	register_widget( 'VAM_Widget_Airport_Runways' );
 });

/**
* VAM_Widget_Airport_Frequencies: Displays list of frequencies for an airport
*/
 class VAM_Widget_Airport_Frequencies extends WP_Widget {

   public function get_airport_frequencies($airport) {

 		$data = array();
 		$count = 0;

 		$sql = "select * from airport_frequencies where airport_ident = '$airport'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['type'] = $row['type'];
 			$data[$count]['description'] = $row['description'];
 			$data[$count]['frequency_mhz'] = $row['frequency_mhz'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_airport_frequencies',
 			'description' => 'VAM Airport Frequencies',
 		);
 		parent::__construct( 'vam_widget_airport_frequencies', 'VAM Airport Frequencies', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->airport = (isset($_GET["airport"])) ? $_GET["airport"] : "";
 		$this->freq_data = $this->get_airport_frequencies($this->airport);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->airport . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-airport-frequencies">
 			<thead>
 				<tr>
        <th>' . __("Type", "vamwp") . '</th>
        <th>' . __("Name", "vamwp") . '</th>
        <th>' . __("Freq", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->freq_data as $freq_details) {

 			echo('<tr>
 				<td>' . $freq_details['type'] . '</td>
 				<td>' . $freq_details['description'] . '</td>
 				<td>' . $freq_details['frequency_mhz'] . 'MHz</td>
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
 	register_widget( 'VAM_Widget_Airport_Frequencies' );
 });

/**
* VAM_Widget_Airport_Navaids: Displays list of navigational aids for an airport
*/
 class VAM_Widget_Airport_Navaids extends WP_Widget {

   public function get_airport_navaids($airport) {

 		$data = array();
 		$count = 0;

 		$sql = "select * from navaids where associated_airport = '$airport'";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['type'] = $row['type'];
 			$data[$count]['name'] = $row['name'];
 			$data[$count]['frequency_khz'] = $row['frequency_khz'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_airport_navaids',
 			'description' => 'VAM Airport Near Navigation Aids',
 		);
 		parent::__construct( 'vam_widget_airport_navaids', 'VAM Airport Near Navigation Aids', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->airport = (isset($_GET["airport"])) ? $_GET["airport"] : "";
 		$this->navaid_data = $this->get_airport_navaids($this->airport);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->airport . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-airport-navaids">
 			<thead>
 				<tr>
        <th>' . __("Type", "vamwp") . '</th>
        <th>' . __("Name", "vamwp") . '</th>
        <th>' . __("Freq", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->navaid_data as $navaid_details) {

 			echo('<tr>
 				<td>' . $navaid_details['type'] . '</td>
 				<td>' . $navaid_details['name'] . '</td>
 				<td>' . $navaid_details['frequency_khz'] . 'KHz</td>
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
 	register_widget( 'VAM_Widget_Airport_Navaids' );
 });

 ?>

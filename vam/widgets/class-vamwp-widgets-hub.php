<?php

/**
 * The hub widgets. Defines hub-specific widgets.
 *
 * Widgets must be placed on a page which receives the hub's VAM ID
 * in the "hub_id" URL paramter.
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
 * VAM_Widget_Airport_Info: Displays link to a hub's airport info
 * VAM_Widget_Hub_Details: Display general information about a hub
 * VAM_Widget_Hub_Routes: Display a list of routes to/from a hub
 * VAM_Widget_Hub_Pilots: Display a list of pilots based at a hub
 * VAM_Widget_Hub_Fleet: Display a list of aircraft based at a hub
 * VAM_Widget_Hub_Routes_Map: Display a map of routes to/from a hub
 */

 /**
 * VAM_Widget_Airport_Info: Displays link to a hub's airport info
 */
  class VAM_Widget_Airport_Info extends WP_Widget {

    public function get_hub_details($hub_id) {

  		$data = array();

  		$sql = "select hub from hubs where hub_id=$hub_id";
  		if (!$result = $this->vam->db->query($sql)) {
  			die('There was an error running the query [' . $this->vam->db->error . ']');
  		}
  		while ($row = $result->fetch_assoc()) {
  			$data["hub_code"] = $row["hub"];
  		}

  		return $data;

  	}

  	/**
  	 * Sets up the widgets name etc
  	 */
  	public function __construct() {
  		$widget_ops = array(
  			'classname' => 'vam_widget_airport_info',
  			'description' => 'VAM Airport Info',
  		);
  		parent::__construct( 'vam_widget_airport_info', 'VAM Airport Info', $widget_ops );
  		$this->vam = new VAMWP_VAM();
  		$this->hub_id = (isset($_GET["hub_id"])) ? $_GET["hub_id"] : "";
  		if ($this->hub_id != "") {
  			$this->hub_data = $this->get_hub_details($this->hub_id);
  			$this->airport = $this->hub_data["hub_code"];
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
  			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . $args['after_title'];
  		}

  		//$va_data = $this->vam->get_va_data();

  		echo('<a href="' . $this->vam->get_vam_url("airport_info", $this->airport) . '">' . __("View airport information", "vamwp") . ' - ' . $this->airport . '</a>');

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
  	register_widget( 'VAM_Widget_Airport_Info' );
  });

/**
* VAM_Widget_Hub_Details: Display general information about a hub
*/
 class VAM_Widget_Hub_Details extends WP_Widget {

   public function get_hub_details($hub_id) {

 		$data = array();

 		$sql = "select * from hubs h inner join airports a on a.ident = h.hub where hub_id=$hub_id";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$data["hub_code"] = $row["hub"];
 			$data["hub_name"] = $row["name"];
 			$data["hub_url"] = $row["web"];
 			$data["hub_image"] = $row["image_url"];
 			$data["hub_country"] = $row["iso_country"];
 		}

 		$sql = "select count(*) num_pilots from gvausers where hub_id=$hub_id and activation=1";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$data["num_pilots"] = $row["num_pilots"];
 		}

 		$sql = "select count(*) num_fleet from fleets where hub_id=$hub_id ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$data["num_fleet"] = $row["num_fleet"];
 		}

 		$sql = "select count(*) num_routes from routes where hub_id=$hub_id ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$data["num_routes"] = $row["num_routes"];
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_hub_details',
 			'description' => 'VAM Hub Details',
 		);
 		parent::__construct( 'vam_widget_hub_details', 'VAM Hub Details', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->hub_id = (isset($_GET["hub_id"])) ? $_GET["hub_id"] : "";
 		if ($this->hub_id != "") {
 			$this->hub_data = $this->get_hub_details($this->hub_id);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->hub_data['hub_code'] . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-hub-details">
          <thead>
          <tr>
            <th>' . __("Item", "vamwp") . '</th>
            <th>' . __("Value", "vamwp") . '</th>
          </tr>
          </thead>
 					<tbody>');

 		if (trim($this->hub_data['hub_image']) != "") {
 			echo('	<tr>
 						<td colspan="2"><img src="' . $this->hub_data['hub_image'] . '" /></td>
 					</tr>');
 		}

 		echo('		<tr>
 						<th>' . __("Name","vamwp") . '</th><td>' . $this->vam->get_flag_icon($this->hub_data['hub_country']) . $this->hub_data['hub_code'] .
 						'<span class="vam-airportname">' . $this->hub_data['hub_name'] . '</span></td>
 					</tr>');

 		if (trim($this->hub_data['hub_url']) != "") {
 			echo('	<tr>
 						<th>' . __("Website","vamwp") . '</th><td><a href="' . $this->hub_data['hub_url'] . '">Link</a></td>
 					</tr>');
 		}

 		echo('		<tr>
 						<th>' . __("Number of Pilots","vamwp") . '</th><td>' . $this->hub_data['num_pilots'] . '</td>
 					</tr>
 					<tr>
 						<th>' . __("Number of Aircraft","vamwp") . '</th><td>' . $this->hub_data['num_fleet'] . '</td>
 					</tr>
 					<tr>
 						<th>' . __("Number of Routes","vamwp") . '</th><td>' . $this->hub_data['num_routes'] . '</td>
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
 	register_widget( 'VAM_Widget_Hub_Details' );
 });

/**
* VAM_Widget_Hub_Routes: Display a list of routes to/from a hub
*/
 class VAM_Widget_Hub_Routes extends WP_Widget {

   public function get_hub_details($hub_id) {

     $data = array();

     $sql = "select hub from hubs where hub_id=$hub_id";
     if (!$result = $this->vam->db->query($sql)) {
       die('There was an error running the query [' . $this->vam->db->error . ']');
     }
     while ($row = $result->fetch_assoc()) {
       $data["hub_code"] = $row["hub"];
     }

     return $data;

   }

   public function get_hub_routes($hub_id) {

 		$data = array();
 		$count = 0;

 		$sql = "select * from routes where hub_id=$hub_id";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['flight'] = $row['flight'];
 			$data[$count]['departure'] = $row['departure'];
 			$data[$count]['arrival'] = $row['arrival'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_hub_routes',
 			'description' => 'VAM Hub Routes',
 		);
 		parent::__construct( 'vam_widget_hub_routes', 'VAM Hub Routes', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->hub_id = (isset($_GET["hub_id"])) ? $_GET["hub_id"] : "";
 		if ($this->hub_id != "") {
 			$this->hub_data = $this->get_hub_details($this->hub_id);
 			$this->route_data = $this->get_hub_routes($this->hub_id);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->hub_data['hub_code'] . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-hub-routes">
 			<thead>
 				<tr>
        <th>' . __("Flight", "vamwp") . '</th>
        <th>' . __("Departure", "vamwp") . '</th>
        <th>' . __("Arrival", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->route_data as $route) {

 			echo('<tr>
 				<td>' . $route['flight'] . '</td>
 				<td>' . $this->vam->get_departure_icon() . '<a href="' . $this->vam->get_vam_url("airport_info", $route['departure']) . '">' .$route['departure'] . '</a></td>
 				<td>' . $this->vam->get_arrival_icon() . '<a href="' . $this->vam->get_vam_url("airport_info", $route['arrival']) . '">' .$route['arrival'] . '</a></td>
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
 	register_widget( 'VAM_Widget_Hub_Routes' );
 });

/**
* VAM_Widget_Hub_Pilots: Display a list of pilots based at a hub
*/
 class VAM_Widget_Hub_Pilots extends WP_Widget {

   public function get_hub_details($hub_id) {

     $data = array();

     $sql = "select hub from hubs where hub_id=$hub_id";
     if (!$result = $this->vam->db->query($sql)) {
       die('There was an error running the query [' . $this->vam->db->error . ']');
     }
     while ($row = $result->fetch_assoc()) {
       $data["hub_code"] = $row["hub"];
     }

     return $data;

   }

   public function get_hub_pilots($hub_id) {

 		$data = array();
 		$count = 0;

 		$sql = "select * from country_t c, gvausers gu, ranks r, hubs h, (select 0 + sum(time) as gva_hours,
 				pilot from v_pilot_roster_rejected vv group by pilot) as v
 	            where
 	            h.hub_id = $hub_id and
 	            gu.rank_id=r.rank_id and
 	            h.hub_id=gu.hub_id and
 	            gu.activation<>0 and
 	            gu.country=c.iso2 and
 	            v.pilot = gu.gvauser_id order by callsign asc";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['gvauser_id'] = $row['gvauser_id'];
 			$data[$count]['callsign'] = $row['callsign'];
 			$data[$count]['name'] = $row['name'];
 			$data[$count]['surname'] = $row['surname'];
 			$data[$count]['location'] = $row['location'];
 			$data[$count]['gva_hours'] = $row['gva_hours'];
 			$data[$count]['transfered_hours'] = $row['transfered_hours'];
 			$data[$count]['rank'] = $row['rank'];
 			$data[$count]['country'] = $row['iso2'];
 			$data[$count]['short_name'] = $row['short_name'];
 			$data[$count]['activation'] = $row['activation'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_hub_pilots',
 			'description' => 'VAM Hub Pilots',
 		);
 		parent::__construct( 'vam_widget_hub_pilots', 'VAM Hub Pilots', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->hub_id = (isset($_GET["hub_id"])) ? $_GET["hub_id"] : "";
 		if ($this->hub_id != "") {
 			$this->hub_data = $this->get_hub_details($this->hub_id);
 			$this->pilot_data = $this->get_hub_pilots($this->hub_id);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->hub_data['hub_code'] . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-hub-pilots">
 			<thead>
 				<tr>
        <th>' . __("Callsign", "vamwp") . '</th>
        <th>' . __("Name", "vamwp") . '</th>
        <th>' . __("Location", "vamwp") . '</th>
        <th>' . __("Hours", "vamwp") . '</th>
        <th>' . __("Rank", "vamwp") . '</th>
        <th>' . __("Country", "vamwp") . '</th>
        <th>' . __("Status", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->pilot_data as $pilot) {

 			echo('<tr>
 				<td><a href="' . $this->vam->get_vam_url("pilot_details", $pilot['gvauser_id']) . '">' . $pilot['callsign'] . '</a></td>
 				<td>' . $pilot['name'] . ' ' . $pilot['surname'] . '</td>
 				<td><a href="' . $this->vam->get_vam_url("airport_info", $pilot['location']) . '">' . $pilot['location'] . '</a></td>
 				<td><span class="fa fa-clock-o fa-fw"></span>' . $this->vam->convertTime(round($pilot['gva_hours'], 2) + round($pilot['transfered_hours'], 2), $this->vam->va_time_format) . '</td>
 				<td>' . $pilot['rank'] . '</td>
 				<td>' . $this->vam->get_flag_icon($pilot['country']) . $pilot['short_name'] . '</td>
 				<td>' . $this->vam->get_pilot_status_icon($pilot['activation']) . '</td>
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
 	register_widget( 'VAM_Widget_Hub_Pilots' );
 });

/**
* VAM_Widget_Hub_Fleet: Display a list of aircraft based at a hub
*/
 class VAM_Widget_Hub_Fleet extends WP_Widget {

   public function get_hub_details($hub_id) {

     $data = array();

     $sql = "select hub from hubs where hub_id=$hub_id";
     if (!$result = $this->vam->db->query($sql)) {
       die('There was an error running the query [' . $this->vam->db->error . ']');
     }
     while ($row = $result->fetch_assoc()) {
       $data["hub_code"] = $row["hub"];
     }

     return $data;

   }

   public function get_hub_fleet($hub_id) {

 		$data = array();
 		$count = 0;

 		$sql = "select registry, status, hours,plane_description, location
 				from fleets f
 				inner join fleettypes ft on f.fleettype_id=ft.fleettype_id
 				where hub_id = $hub_id";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['registry'] = $row['registry'];
 			$data[$count]['status'] = $row['status'];
 			$data[$count]['hours'] = $row['hours'];
 			$data[$count]['plane_description'] = $row['plane_description'];
 			$data[$count]['location'] = $row['location'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_hub_fleet',
 			'description' => 'VAM Hub Fleet',
 		);
 		parent::__construct( 'vam_widget_hub_fleet', 'VAM Hub Fleet', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->hub_id = (isset($_GET["hub_id"])) ? $_GET["hub_id"] : "";
 		if ($this->hub_id != "") {
 			$this->hub_data = $this->get_hub_details($this->hub_id);
 			$this->fleet_data = $this->get_hub_fleet($this->hub_id);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->hub_data['hub_code'] . $args['after_title'];
 		}

 		//$va_data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-hub-fleet">
 			<thead>
 				<tr>
        <th>' . __("Registry", "vamwp") . '</th>
        <th>' . __("Type", "vamwp") . '</th>
        <th>' . __("Location", "vamwp") . '</th>
        <th>' . __("Hours", "vamwp") . '</th>
        <th>' . __("Status", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->fleet_data as $fleet) {

 			echo('<tr>
 				<td><a href="' . $this->vam->get_vam_url("plane_info_public", $fleet['registry']) . '">' . $fleet['registry'] . '</a></td>
 				<td>' . $fleet['plane_description'] . '</td>
 				<td><a href="' . $this->vam->get_vam_url("airport_info", $fleet['location']) . '">' . $fleet['location'] . '</a></td>
 				<td><span class="fa fa-clock-o fa-fw"></span>' . $fleet['hours'] . '</td>
 				<td>' . $fleet['status'] . '%</td>
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
 	register_widget( 'VAM_Widget_Hub_Fleet' );
 });

/**
* VAM_Widget_Hub_Routes_Map: Display a map of routes to/from a hub
*/
 class VAM_Widget_Hub_Routes_Map extends WP_Widget {

   public function get_hub_flights_map($hub_id) {

 		$data = array();
 		$count = 0;

    $sql = "SELECT * FROM routes INNER JOIN airports ON airports.ident = routes.arrival  WHERE hub_id = $hub_id";
    if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
  	while ($row = $result->fetch_assoc()) {
  		$data[$count] = array ($row["latitude_deg"],  $row["longitude_deg"] ,  $row["ident"],  $row["name"]) ;
      $count++;
  	}

    return $data;

  }

  public function get_hub_flights_map_latlong($hub_id) {

    $data = array();
    $count = 0;

    $sql = "SELECT * FROM  hubs h  INNER JOIN airports a on a.ident=h.hub WHERE h.hub_id = $hub_id ";
    if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
  	while ($row = $result->fetch_assoc()) {
  		$data['lat_centro'] = $row["latitude_deg"];
  		$data['long_centro'] = $row["longitude_deg"];
  	}

    return $data;

  }

   public function get_hub_details($hub_id) {

     $data = array();

     $sql = "select hub from hubs where hub_id=$hub_id";
     if (!$result = $this->vam->db->query($sql)) {
       die('There was an error running the query [' . $this->vam->db->error . ']');
     }
     while ($row = $result->fetch_assoc()) {
       $data["hub_code"] = $row["hub"];
     }

     return $data;

   }

/*   public function get_hub_routes_map_url($hub_id) {

 		return $this->vam->vam_url_path . "hub_routes_map.php?hub_id=" . $hub_id;

 	}*/

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_hub_routes_map',
 			'description' => 'VAM Hub Routes Map',
 		);
 		parent::__construct( 'vam_widget_hub_routes_map', 'VAM Hub Routes Map', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->hub_id = (isset($_GET["hub_id"])) ? $_GET["hub_id"] : "";
 		if ($this->hub_id != "") {
 			$this->hub_data = $this->get_hub_details($this->hub_id);
// 			$this->routes_map_url = $this->get_hub_routes_map_url($this->hub_id);
      $this->hub_map_data = $this->get_hub_flights_map($this->hub_id);
      $this->hub_map_latlong = $this->get_hub_flights_map_latlong($this->hub_id);

      add_action('wp_head', function() {
        echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . get_option("mt_vam_googlemaps_api_key") . '&callback=init_map" type="text/javascript"></script>';
   			echo '<script type="text/javascript">
            var b;
   					function init_map() {
   						var locations = ' . json_encode($this->hub_map_data) . ';
   						var var_location = new google.maps.LatLng(' . $this->hub_map_latlong["lat_centro"] . ',' . $this->hub_map_latlong["long_centro"] . ');
              var var_mapoptions = {
          			center: var_location,
          			zoom: 5,
          			styles: [{featureType:"road",elementType:"geometry",stylers:[{lightness:100},{visibility:"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#C6E2FF",}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#C5E3BF"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#D1D1B8"}]}]
          		};
   						var var_map = new google.maps.Map(document.getElementById("map-container"),var_mapoptions);
              var k=0;
          		var arr_long= locations.length;
          		while (k<arr_long) {
          			dep = var_location;
          			arr = new google.maps.LatLng(locations[k][0], locations[k][1]);
          			var icon_red = "images/airport_runway_red.png";
          			var icon_green = "images/airport_runway_green.png";
          			var icon_red = "";
          			var icon_green = "";
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
          			k++;
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . ' - ' . $this->hub_data['hub_code'] . $args['after_title'];
 		}

    echo ( '<div class="container">
 					<div class="row">
 						<div id="map-outer" class="col-md-11">
 							<div id="map-container" class="col-md-12"></div>
 						</div>
 					</div>
 				</div>');


/* 		echo ( '<table class="vam-datatable display" id="vam-hub-routes-map">
 			<thead><thead>
 			<tbody>');

 			echo('<tr>
 				<td><iframe src="' . $this->routes_map_url . '" width="100%" height="500px"></iframe></td>
 			</tr>');

 		echo ( '</tbody>
 		</table>');
 		echo $args['after_widget'];*/
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
 	register_widget( 'VAM_Widget_Hub_Routes_Map' );
 });

?>

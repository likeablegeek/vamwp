<?php

/**
 * The airline widgets. Defines widgets for generic airline information not
 * dependent on specific pilots, hubs, routes, etc.
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
 * VAM_Widget_Stats: Displays general airline statistics
 * VAM_Widget_LatestFlights: Displays five most recent flights
 * VAM_Widget_NewestPilots: Displays five most recent pilots
 * VAM_Widget_PilotsRoster: Displays complete list of all active pilots
 * VAM_Widget_Routes: Displays complete list of all routes
 * VAM_Widget_Fleet: Displays complete list of all fleet
 * VAM_Widget_Tours: Displays complete list of all tours
 * VAM_Widget_Ranks: Displays complete list of all ranks
 * VAM_Widget_Awards: Displays complete list of all awards
 * VAM_Widget_Hubs: Displays complete list of all hubs
 */

/**
* VAM_Widget_Stats: Displays general airline Statistics
*/
 class VAM_Widget_Stats extends WP_Widget {

   public function get_va_data() {

 		$data = array();

 		$no_count_rejected=0;

 		$sql = "select * from va_parameters ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$no_count_rejected = $row["no_count_rejected"];
 		}

 		//  Get count number of manual pireps for pilot's stadistics
 		$sql = "select count(pirep_id) numpireps from pireps ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_pireps = $row["numpireps"];
 		}

 		//  Get count number of fsacars pireps for pilot's stadistics
 		$sql = "select count(report_id) numpireps from reports ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_reports = $row["numpireps"];
 		}

 		//  Get count number of fsacars regular pireps for pilot's stadistics
 		$sql = "select count(report_id) numpireps from reports where charter=0";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_reports_reg = $row["numpireps"];
 		}

 		//  Get count number of regular manual pireps for pilot's stadistics
 		$sql = "select count(pirep_id) numpireps from pireps where charter=0";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_pireps_reg = $row["numpireps"];
 		}

 		//  Get distancer of regular manual pireps for pilot's stadistics
 		$sql = "select sum(distance) distance from pireps";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$dist_pireps = $row["distance"];
 		}

 		// Get FS FSACARS flights for pilot's stadistics
 		$sql = "select count(*) flights from reports";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_fsacars = $row["flights"];
 		}

 		// Get Regular FS FSACARS flights for pilot's stadistics
 		$sql = "select count(*) flights from reports where charter=0";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_fsacars_reg = $row["flights"];
 		}

 		// Get VAM ACARS flights for pilot's stadistics
 		$sql = "select count(*) flights from vampireps ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_vamacars = $row["flights"];
 		}

 		// Get Regular VAM ACARS flights for pilot's stadistics
 		$sql = "select count(*) flights from vampireps where charter=0";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_vamacars_reg = $row["flights"];
 		}

 		// Get FS Keeper distance for pilot's stadistics
 		$sql = "select sum(distance) distancefsacars from reports ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$dist_fsacars = $row["distancefsacars"];
 		}

 		// Get FS Keeper flights for pilot's stadistics
 		$sql = "select count(*) flights from pirepfsfk";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_fskeeper = $row["flights"];
 		}

 		// Get Regular FS Keeper flights for pilot's stadistics
 		$sql = "select count(*) flights from pirepfsfk where charter=0";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$num_fskeeper_reg = $row["flights"];
 		}

 		// Get FS Keeper distance for pilot's stadistics
 		$sql = "select sum(DistanceRoute) distance from pirepfsfk ";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$dist_fskeeper = $row["distance"];
 		}

 		$sql = "select count(*) num_pilots from gvausers where activation=1";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) { $num_pilots = $row["num_pilots"]; }

 		$sql = "select count(*) num_planes from fleets";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) { $num_planes = $row["num_planes"]; }

 		$sql = "select count(*) num_routes from routes";
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) { $num_routes = $row["num_routes"]; }


 		// Get rejected flights to be discounted
 		$num_pireps_rejected= 0;
 		$num_pireps_reg_rejected = 0;
 		$dist_pireps_rejected = 0 ;
 		$num_fskeeper_rejected =  0;
 		$num_fskeeper_reg_rejected = 0;
 		$dist_fskeeper_rejected = 0;
 		$num_fsacars_rejected = 0;
 		$num_fsacars_reg_rejected = 0;
 		$dist_fsacars_rejected =  0;
 		$num_vamacars_rejected =  0;
 		$dist_vamacars_rejected =  0;
 		$num_vamacars_reg_rejected = 0;

 		if ($no_count_rejected==1) {
 			//  Get count number of manual pireps
 			$sql = "select count(pirep_id) numpireps from pireps where  valid=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_pireps_rejected = $row["numpireps"];
 			}
 			//  Get count number of regular manual pireps
 			$sql = "select count(pirep_id) numpireps from pireps where charter=0 and valid=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_pireps_reg_rejected = $row["numpireps"];
 			}
 			//  Get distancer of regular manual pireps
 			$sql = "select sum(distance) distance from pireps where valid=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$dist_pireps_rejected = $row["distance"];
 			}
 			// Get FS Keeper flights
 			$sql = "select count(*) flights from pirepfsfk where validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_fskeeper_rejected = $row["flights"];
 			}
 			// Get Regular FS Keeper flights
 			$sql = "select count(*) flights from pirepfsfk where charter=0 and validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_fskeeper_reg_rejected = $row["flights"];
 			}
 			// Get FS Keeper distance
 			$sql = "select sum(DistanceRoute) distance from pirepfsfk where  validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$dist_fskeeper_rejected = $row["distance"];
 			}
 			// Get FS ACARS flights
 			$sql = "select count(*) flights from reports where validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_fsacars_rejected = $row["flights"];
 			}
 			// Get Regular FS ACARS flights
 			$sql = "select count(*) flights from reports where charter=0 and validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_fsacars_reg_rejected = $row["flights"];
 			}
 			// Get VAM ACARS flights
 			$sql = "select count(*) flights from vampireps where validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_vamacars_rejected = $row["flights"];
 			}
 			//  Get distancer of VAM ACARS
 			$sql = "select sum(distance) distance from vampireps where validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$dist_vamacars_rejected = $row["distance"];
 			}
 			// Get Regular VAM ACARS flights
 			$sql = "select count(*) flights from vampireps where  charter=0 and validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$num_vamacars_reg_rejected = $row["flights"];
 			}
 			// Get FS ACARS distance
 			$sql = "select sum(distance) as distance from reports where validated=2";
 			if (!$result = $this->vam->db->query($sql)) {
 				die('There was an error running the query [' . $this->vam->db->error . ']');
 			}
 			while ($row = $result->fetch_assoc()) {
 				$dist_fsacars_rejected = $row["distance"];
 			}
 		}
 		if ($no_count_rejected==1) {
 			$sql = "select  sum(v.sum_time + g.transfered_hours) as total_time from v_top_hours_rejected v inner join gvausers g on g.gvauser_id = v.pilot";
 		} else {
 			$sql = "select  sum(v.sum_time + g.transfered_hours) as total_time from v_top_hours v inner join gvausers g on g.gvauser_id = v.pilot";
 		}
 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}
 		while ($row = $result->fetch_assoc()) {
 			$va_hours = $row["total_time"];
 		}

 		$data['num_pilots']			=	$num_pilots;
 		$data['num_planes']			=	$num_planes;
 		$data['num_routes']			=	$num_routes;
 		$data['num_pireps']			=	$num_pireps;
 		$data['num_pireps_reg']		=	$num_pireps_reg;
 		$data['dist_pireps']			=	$dist_pireps;
 		$data['num_reports']			=	$num_reports;
 		$data['num_fsacars']			=	$num_fsacars;
 		$data['num_fsacars_reg']		=	$num_fsacars_reg;
 		$data['dist_fsacars']		=	$dist_fsacars;
 		$data['num_fskeeper']		=	$num_fskeeper;
 		$data['num_fskeeper_reg']		=	$num_fskeeper_reg;
 		$data['dist_fskeeper']		=	$dist_fskeeper;
 		$data['num_vamacars']		=	$num_vamacars;
 		$data['num_vamacars_reg']		=	$num_vamacars_reg;
 		$data['va_hours']			=	$this->vam->convertTime($va_hours,$this->vam->va_time_format);

 		$data['num_pireps_rejected']		=	$num_pireps_rejected;
 		$data['num_pireps_reg_rejected']	=	$num_pireps_reg_rejected;
 		$data['dist_pireps_rejected']	=	$dist_pireps_rejected;
 		$data['num_fskeeper_rejected']	=	$num_fskeeper_rejected;
 		$data['num_fskeeper_reg_rejected']	=	$num_fskeeper_reg_rejected;
 		$data['dist_fskeeper_rejected']	=	$dist_fskeeper_rejected;
 		$data['num_fsacars_rejected']	=	$num_fsacars_rejected;
 		$data['num_fsacars_reg_rejected']	=	$num_fsacars_reg_rejected;
 		$data['dist_fsacars_rejected']	=	$dist_fsacars_rejected;
 		$data['num_vamacars_rejected']	=	$num_vamacars_rejected;
 		$data['num_vamacars_reg_rejected']	=	$num_vamacars_reg_rejected;
 		$data['dist_vamacars_rejected']	=	$dist_vamacars_rejected;
 		$data['num_flights']			=	$num_fskeeper + $num_pireps + $num_reports + $num_vamacars - $num_fsacars_rejected - $num_fskeeper_rejected - $num_pireps_rejected - $num_vamacars_rejected ;
 		$data['num_flights_reg']		=	$num_fskeeper_reg + $num_pireps_reg + $num_reports_reg + $num_vamacars_reg - $num_pireps_reg_rejected - $num_fskeeper_reg_rejected - $num_fsacars_reg_rejected - $num_vamacars_reg_rejected;
 		$data['num_flights_charter']		=	$num_pireps + $num_fskeeper + $num_fsacars + $num_vamacars - $num_pireps_reg - $num_fskeeper_reg - $num_fsacars_reg - $num_vamacars_reg ;
 		$data['perc_flights_reg']		=	($data['num_flights'] < 1) ? '0 %' :  number_format((100 * $data['num_flights_reg']) / $data[ 'num_flights'], 2) . ' %';

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_stats',
 			'description' => 'VAM Statistics',
 		);
 		parent::__construct( 'vam_widget_stats', 'VAM Statistics', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->va_data = $this->get_va_data();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable row-border" id="vam-stats">
 			<thead>
 			<tr>
 				<th>' . __("Item", "vamwp") . '</th>
 				<th>' . __("Value", "vamwp") . '</th>
 			</tr>
 			</thead>
 			<tbody>
 			<tr>
 				<td class="vam_tdhead"><span class="fa fa-users fa-fw"></span>' . __("Pilots", "vamwp") . '</td>
 				<td>' . $this->va_data['num_pilots'] . '</td>
 			</tr>
 			<tr>
 				<td class="vam_tdhead"><span class="fa fa-plane fa-fw"></span>' . __("Aircraft in Fleet", "vamwp") . '</td>
 				<td>' . $this->va_data['num_planes'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead"><span class="fa fa-globe fa-fw"></span>' . __("Routes", "vamwp") . '</td>
 				<td>' . $this->va_data['num_routes'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead"><span class="fa fa-clock-o fa-fw"></span>' . __("Total Hours", "vamwp") . '</td>
 				<td>' . $this->va_data['va_hours'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead"><span class="fa fa-suitcase fa-fw"></span>' . __("Flights Total", "vamwp") . '</td>
 				<td>' . $this->va_data['num_flights'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead"><span class="fa fa-exchange fa-fw"></span>' . __("Flights Regular", "vamwp") . '</td>
 				<td>' . $this->va_data['num_flights_reg'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead"><span class="fa fa-code-fork fa-fw"></span>' . __("Flights Charter", "vamwp") . '</td>
 				<td>' . $this->va_data['num_flights_charter'] . '</td>
 			</tr>
  			<tr>
 				<td class="vam_tdhead"><span class="fa fa-bar-chart fa-fw"></span>' . __("% Flights Regular", "vamwp") . '</td>
 				<td>' . $this->va_data['perc_flights_reg'] . '</td>
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
 	register_widget( 'VAM_Widget_Stats' );
 });

/**
* VAM_Widget_LatestFlights: Displays give most recent flights
*/
 class VAM_Widget_LatestFlights extends WP_Widget {

   public function get_latest_flights() {

 		$data = array();
 		$count = 0;

 		$sql = "select gvauser_id,a1.name as dep_name, a2.name as arr_name, a1.iso_country as
 			dep_country,a2.iso_country as arr_country,callsign,pilot_name,departure,
 			arrival,DATE_FORMAT(date,'" . $this->vam->va_date_format . "') as date_string, date,
 			format(time,2) as time from v_last_5_flights v, airports a1, airports a2
 			where v.departure=a1.ident and v.arrival=a2.ident and time is not null
 			order by date desc";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['gvauser_id'] = $row['gvauser_id'];
 			$data[$count]['dep_name'] = $row['dep_name'];
 			$data[$count]['arr_name'] = $row['arr_name'];
 			$data[$count]['dep_country'] = $row['dep_country'];
 			$data[$count]['arr_country'] = $row['arr_country'];
 			$data[$count]['callsign'] = $row['callsign'];
 			$data[$count]['pilot_name'] = $row['pilot_name'];
 			$data[$count]['departure'] = $row['departure'];
 			$data[$count]['arrival'] = $row['arrival'];
 			$data[$count]['date_string'] = $row['date_string'];
 			$data[$count]['date'] = $row['date'];
 			$data[$count]['time'] = $row['time'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_latestflights',
 			'description' => 'VAM Latest Flights',
 		);
 		parent::__construct( 'vam_widget_latestflights', 'VAM Latest Flights', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->flight_data = $this->get_latest_flights();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-latestflights">
 			<thead>
 				<tr>
 					<th>' . __("Callsign", "vamwp") . '</th>
 					<th>' . __("Pilot", "vamwp") . '</th>
 					<th>' . __("Departure", "vamwp") . '</th>
 					<th>' . __("Arrival", "vamwp") . '</th>
 					<th>' . __("Date", "vamwp") . '</th>
 					<th>' . __("Flight Time", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->flight_data as $flight) {

 			echo('<tr>
 				<td>' . $flight['callsign'] . '</td>
 				<td>' . $flight['pilot_name'] . '</td>
 				<td>' . $this->vam->get_departure_icon() . $this->vam->get_flag_icon($flight['dep_country']) . '<a
 						href="' . $this->vam->get_vam_url('airport_info',$flight['departure']) . '">' . $flight['departure'] . '</a></td>
 				<td>' . $this->vam->get_arrival_icon() . $this->vam->get_flag_icon($flight['arr_country']) . '<a
 						href="' . $this->vam->get_vam_url('airport_info',$flight['arrival']) . '">' . $flight['arrival'] . '</a></td>
 				<td>' . $flight['date_string'] . '</td>
 				<td>' . $flight['time'] . '</td>
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
 	register_widget( 'VAM_Widget_LatestFlights' );
 });

/**
* VAM_Widget_NewestPilots: Displays five most recent pilots
*/
 class VAM_Widget_NewestPilots extends WP_Widget {

   public function get_newest_pilots() {

 		$data = array();
 		$count = 0;

 		$sql = "select gvauser_id, concat(callsign,'-',name,' ',surname) as pilot ,
 			DATE_FORMAT(register_date,'" . $this->vam->va_date_format . "') as register_date from
 			gvausers where activation=1 order by DATE_FORMAT(register_date,'%Y%m%d')
 			desc limit 5";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['gvauser_id'] = $row['gvauser_id'];
 			$data[$count]['pilot'] = $row['pilot'];
 			$data[$count]['register_date'] = $row['register_date'];

 			$count++;
 		}

 		return $data;

 	}


 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_newestpilots',
 			'description' => 'VAM Newest Pilots',
 		);
 		parent::__construct( 'vam_widget_newestpilots', 'VAM Newest Pilots', $widget_ops );
 		$this->vam = new VAMWP_VAM();
    $this->pilot_data = $this->get_newest_pilots();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-newestpilots">
 			<thead>
 				<tr>
 					<th>' . __("Pilot", "vamwp") . '</th>
 					<th>' . __("Joined", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->pilot_data as $pilot) {

 			echo('<tr>
 				<td><a href="' . $this->vam->get_vam_url('pilot_details',$pilot['gvauser_id']) . '">' . $pilot['pilot'] . '</a></td>
 				<td>' . $pilot['register_date'] . '</td>
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
 	register_widget( 'VAM_Widget_NewestPilots' );
 });

/**
* VAM_widget_PilotsRoster: Displays complete list of all active pilots
*/
 class VAM_Widget_PilotsRoster extends WP_Widget {

   public function get_pilots_roster() {

 		$data = array();
 		$count = 0;

 		$sql = "select a.name as airport_name, iso_country, gu.hub_id as hubid,v.gva_hours,
 					transfered_hours,gvauser_id,callsign,surname,activation,vatsimid,ivaovid ,
 					transfered_hours, rank, gu.name as name,hub,location, r.image_url as rank_image,
 					iso2, short_name from country_t c, gvausers gu, ranks r, hubs h,
 					(select 0 + sum(time) as gva_hours, pilot from
 					v_pilot_roster vv group by pilot) as v , airports a where a.ident=gu.location and
 					gu.rank_id=r.rank_id and h.hub_id=gu.hub_id and gu.activation<>0 and
 					gu.country=c.iso2 and v.pilot = gu.gvauser_id order by callsign asc";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['gvauser_id'] = $row['gvauser_id'];
 			$data[$count]['airport_name'] = $row['airport_name'];
 			$data[$count]['iso_country'] = $row['iso_country'];
 			$data[$count]['hubid'] = $row['hubid'];
 			$data[$count]['gva_hours'] = $row['gva_hours'];
 			$data[$count]['transfered_hours'] = $row['transfered_hours'];
 			$data[$count]['name'] = $row['name'];
 			$data[$count]['hub'] = $row['hub'];
 			$data[$count]['location'] = $row['location'];
 			$data[$count]['rank_image'] = $row['rank_image'];
 			$data[$count]['iso2'] = $row['iso2'];
 			$data[$count]['short_name'] = $row['short_name'];
 			$data[$count]['callsign'] = $row['callsign'];
 			$data[$count]['surname'] = $row['surname'];
 			$data[$count]['activation'] = $row['activation'];
 			$data[$count]['vatsimid'] = $row['vatsimid'];
 			$data[$count]['ivaoid'] = $row['ivaoid'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_pilotsroster',
 			'description' => 'VAM Pilots Roster',
 		);
 		parent::__construct( 'vam_widget_pilotsroster', 'VAM Pilots Roster', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->pilot_data = $this->get_pilots_roster();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-pilotsroster">
 			<thead>
 				<tr>
 					<th>' . __("Callsign", "vamwp") . '</th>
 					<th>' . __("Name", "vamwp") . '</th>
 					<th>' . __("Hub", "vamwp") . '</th>
 					<th>' . __("Location", "vamwp") . '</th>
 					<th>' . __("Hours", "vamwp") . '</th>
 					<th>' . __("Rank", "vamwp") . '</th>
 					<th>' . __("Country", "vamwp") . '</th>
 					<th>' . __("Status", "vamwp") . '</th>
 					<th>' . __("IVAO", "vamwp") . '</th>
 					<th>' . __("VATSIM", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->pilot_data as $pilot) {

 			echo('<tr>
 				<td><a href="' . $this->vam->get_vam_url('pilot_details',$pilot['gvauser_id']) . '">' . $pilot['callsign'] . '</a></td>
 				<td>' . $pilot['name'] . ' ' . $pilot['surname'] .'</td>
 				<td><a href="' . $this->vam->get_vam_url('hub',$pilot['hubid']) . '">' . $pilot['hub'] . '</td>
 				<td>' . $this->vam->get_flag_icon($pilot['iso_country']) . '<a href="' . $this->vam->get_vam_url('airport_info',$pilot['location']) . '">' .$pilot['location'] . '</a><span
 					class="vam-airportname">' . str_replace("Airport","",$pilot["airport_name"]) . '</span></td>
 				<td><span class="fa fa-clock-o fa-fw"></span>' . $this->vam->convertTime($pilot['gva_hours'],$this->vam->va_time_format) . '</td>
 				<td><img height="50px" src="' . $pilot['rank_image'] . '" alt="" /></td>
 				<td>' . $this->vam->get_flag_icon($pilot['iso2']) . $pilot['short_name'] . '</td>
 				<td>' . (($pilot['activation'] == 1) ? __("Active", "vamwp") : __("Inactive", "vamwp")) . '</td>
 				<td>' . (($pilot['ivaoid'] > 0) ? $pilot['ivaoid'] : '') . '</td>
 				<td>' . (($pilot['vatsimid'] > 0) ? $pilot['vatsimid'] : '') . '</td>
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
 	register_widget( 'VAM_Widget_PilotsRoster' );
 });

/**
* VAM_Widget_Routes: Displays complete list of all routes
*/
 class VAM_Widget_Routes extends WP_Widget {

   public function get_routes() {

 		$data = array();
 		$count = 0;

 		$sql = "select flight, a1.name as dep_name, a2.name as arr_name, a1.iso_country as dep_country,
 				a2.iso_country as arr_country,route_id,departure,arrival, duration from routes r, airports a1 ,
 				airports a2 where departure=a1.ident and arrival=a2.ident order by departure asc,arrival asc ";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['flight'] = $row['flight'];
 			$data[$count]['dep_name'] = $row['dep_name'];
 			$data[$count]['arr_name'] = $row['arr_name'];
 			$data[$count]['dep_country'] = $row['dep_country'];
 			$data[$count]['arr_country'] = $row['arr_country'];
 			$data[$count]['route_id'] = $row['route_id'];
 			$data[$count]['departure'] = $row['departure'];
 			$data[$count]['arrival'] = $row['arrival'];
 			$data[$count]['duration'] = $row['duration'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_routes',
 			'description' => 'VAM Routes',
 		);
 		parent::__construct( 'vam_widget_routes', 'VAM Routes', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->route_data = $this->get_routes();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-routes">
 			<thead>
 				<tr>
 					<th>' . __("Flight", "vamwp") . '</th>
 					<th>' . __("Departure", "vamwp") . '</th>
 					<th>' . __("Arrival", "vamwp") . '</th>
 					<th>' . __("Duration", "vamwp") . '</th>
 					<th>' . __("Plane Type", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->route_data as $route) {

 			echo('<tr>
 				<td>' . $route['flight'] . '</td>
 				<td>'  . $this->vam->get_departure_icon() . $this->vam->get_flag_icon($route['dep_country']) . '<a
 					href="' . $this->vam->get_vam_url('airport_info',$route['departure']) . '">' . $route['departure'] . '</a>
 					<span class="vam-airportname">' . str_replace("Airport","",$route["dep_name"]) . '</span></td>
 				<td>' . $this->vam->get_arrival_icon() . $this->vam->get_flag_icon($route['arr_country']) . '<a
 					href="' . $this->vam->get_vam_url('airport_info',$route['arrival']) . '">' . $route['arrival'] . '</a>
 					<span class="vam-airportname">' . str_replace("Airport","",$route["arr_name"]) . '</span></td>
 				<td>' . $this->vam->convertTime($route['duration'],$this->vam->va_time_format) . '</td>
 				<td>' . $this->vam->get_plane_icaos($route['route_id']) . '</td>
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
 	register_widget( 'VAM_Widget_Routes' );
 });

/**
* VAM_Widget_Fleet: Displays complete list of all fleet
*/
 class VAM_Widget_Fleet extends WP_Widget {

   public function get_fleet($icao = "", $location = "") {

 		$data = array();
 		$count = 0;

 		$callsign='';

 		$sql = "select a.name as airport_name, iso_country ,gv.callsign as callsign , f.gvauser_id, hu.hub_id,hub,
 				ha.status as hangar,f.registry as registry,f.status,f.hours,f.name, f.booked ,
 				ft.plane_icao, f.location
 				from fleets f left outer join (select registry,status from hangar where status=1) ha
 				on f.registry = ha.registry
 				inner join  fleettypes ft on f.fleettype_id=ft.fleettype_id
 				inner join hubs hu on hu.hub_id = f.hub_id
 				left outer join gvausers gv on f.gvauser_id = gv.gvauser_id
 				inner join airports a on a.ident=f.location";

 		if (!trim($icao) == "") {
 			$sql = $sql . " where ft.plane_icao ='$icao' order by plane_icao, f.location,f.registry asc";
 		} else if (!trim($location) == "") {
 			$sql = $sql . " where f.location ='$location' order by plane_icao, f.location,f.registry asc";
 		}


 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['airport_name'] = $row['airport_name'];
 			$data[$count]['iso_country'] = $row['iso_country'];
 			$data[$count]['callsign'] = $row['callsign'];
 			$data[$count]['gvauser_id'] = $row['fvauser_id'];
 			$data[$count]['hub_id'] = $row['hub_id'];
 			$data[$count]['hub'] = $row['hub'];
 			$data[$count]['hangar'] = $row['hangar'];
 			$data[$count]['registry'] = $row['registry'];
 			$data[$count]['status'] = $row['status'];
 			$data[$count]['hours'] = $row['hours'];
 			$data[$count]['name'] = $row['name'];
 			$data[$count]['booked'] = $row['booked'];
 			$data[$count]['plane_icao'] = $row['plane_icao'];
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
 			'classname' => 'vam_widget_fleet',
 			'description' => 'VAM Fleet',
 		);
 		parent::__construct( 'vam_widget_fleet', 'VAM Fleet', $widget_ops );
 		$this->vam = new VAMWP_VAM();

 		$this->icao = (isset($_GET["plane_icao"])) ? $_GET["plane_icao"] : "";
 		$this->location = (isset($_GET["plane_location"])) ? $_GET["plane_location"] : "";
 		$this->fleet_data = $this->get_fleet($this->icao,$this->location);
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
 			echo $args['before_title'] . apply_filters('widget_title',$instance['title']) .
 			((!trim($this->icao) == "") ? (" - TYPE " . $this->icao) : "") .
 			((!trim($this->location) == "") ? (" - LOCATION " . $this->location) : "") .
 			$args['after_title'];
 		}

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-fleet">
 			<thead>
 				<tr>
 					<th>' . __("Tail Number", "vamwp") . '</th>
 					<th>' . __("Type", "vamwp") . '</th>
 					<th>' . __("Location", "vamwp") . '</th>
 					<th>' . __("Hub", "vamwp") . '</th>
 					<th>' . __("Status", "vamwp") . '</th>
 					<th>' . __("Hours", "vamwp") . '</th>
 					<th>' . __("Name", "vamwp") . '</th>
 					<th>' . __("Booked", "vamwp") . '</th>
 					<th>' . __("Info", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->fleet_data as $fleet) {

 			$booked_value = "Available";
 			$booked_class = "vam_fleet_booked_free";

 			if ($fleet['hanger'] == 1) {
 				$booked_value = "In Maintenance";
 				$booked_class = "vam_fleet_booked_maintenance";
 			} else if ($fleet['booked'] == 1) {
 				$booked_value = "Booked - " . $fleet['callsign'];
 				$booked_class = "vam_fleet_booked_booked";
 			}

 			echo('<tr>
 				<td><a href="' . $this->vam->get_vam_url("plane_info_public", $fleet['registry']) . '">' . $fleet['registry'] . '</a></td>
 				<td>' . $fleet['plane_icao'] . '</td>
 				<td>' . $this->vam->get_flag_icon($fleet['iso_country']) . '<a href="' . $this->vam->get_vam_url("airport_info", $fleet['location']) . '">' .$fleet['location'] . '</a><span
 					class="vam-airportname">' . str_replace("Airport","",$fleet["airport_name"]) . '</span></td>
 				<td><a href="' . $this->vam->get_vam_url("hub", $fleet['hub_id']) . '">' . $fleet['hub'] . '</a></td>
 				<td>' . $fleet['status'] . '%</td>
 				<td><span class="fa fa-clock-o fa-fw"></span>' . $this->vam->convertTime($fleet['hours'],$this->vam->va_time_format) . '</td>
 				<td>' . $fleet['name'] . '</td>
 				<td><span class=' . $booked_class . '>' . $booked_value . '</span></td>
 				<td><a href="' . $this->vam->get_vam_url("plane_info_public", $fleet['registry']) . '">'. $this->vam->get_info_icon() . '</td>
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
 	register_widget( 'VAM_Widget_Fleet' );
 });

/**
* VAM_Widget_Tours: Displays complete list of all tours
*/
 class VAM_Widget_Tours extends WP_Widget {

   public function get_tours() {

 		$data = array();
 		$count = 0;

 		$sql = "select t.tour_id,  t.tour_name, DATE_FORMAT(t.start_date,'va_date_format') as start_date,
 				DATE_FORMAT(t.end_date,'va_date_format') as end_date, t1.tour_lenght as tour_len, t2.num_leg
 				as legs from tours t INNER JOIN (select t.tour_id,sum(leg_length) as tour_lenght from tours t
 				inner join tour_legs tl on t.tour_id = tl.tour_id GROUP BY tour_id) t1 on t1.tour_id = t.tour_id
 				INNER JOIN (select t.tour_id,count(tour_leg_id) as num_leg from tours t inner join tour_legs tl
 				on t.tour_id = tl.tour_id GROUP BY tour_id) t2 on t.tour_id = t2.tour_id";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['tour_id'] = $row['tour_id'];
 			$data[$count]['tour_name'] = $row['tour_name'];
 			$data[$count]['start_date'] = $row['start_date'];
 			$data[$count]['end_date'] = $row['end_date'];
 			$data[$count]['tour_len'] = $row['tour_len'];
 			$data[$count]['legs'] = $row['legs'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_tours',
 			'description' => 'VAM Tours',
 		);
 		parent::__construct( 'vam_widget_tours', 'VAM Tours', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->tour_data = $this->get_tours();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-tours">
 			<thead>
 				<tr>
        <th>' . __("Tour", "vamwp") . '</th>
        <th>' . __("Start Date", "vamwp") . '</th>
        <th>' . __("End Date", "vamwp") . '</th>
        <th>' . __("Num. Legs", "vamwp") . '</th>
        <th>' . __("Distance", "vamwp") . '</th>
        <th>' . __("Info", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->tour_data as $tour) {

 			echo('<tr>
 				<td>' . $tour['tour_name'] . '</td>
 				<td>' . $tour['start_date'] . '</td>
 				<td>' . $tour['end_date'] . '</td>
 				<td>' . $tour['legs'] . '</td>
 				<td>' . $tour['tour_len'] . '</td>
 				<td><a href="' . $this->vam->get_vam_url("tour_detail", $tour['tour_id']) . '">'. $this->vam->get_info_icon() . '</td>
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
 	register_widget( 'VAM_Widget_Tours' );
 });

/**
* VAM_Widget_Ranks: Displays complete list of all ranks
*/
 class VAM_Widget_Ranks extends WP_Widget {

   public function get_ranks() {

 		$data = array();
 		$count = 0;

 		$sql = "select * from ranks order by minimum_hours asc";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['rank'] = $row['rank'];
 			$data[$count]['image_url'] = $row['image_url'];
 			$data[$count]['minimum_hours'] = $row['minimum_hours'];
 			$data[$count]['maximum_hours'] = $row['maximum_hours'];
 			$data[$count]['salary_hour'] = $row['salary_hour'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_ranks',
 			'description' => 'VAM Ranks',
 		);
 		parent::__construct( 'vam_widget_ranks', 'VAM Ranks', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->rank_data = $this->get_ranks();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-ranks">
 			<thead>
 				<tr>
        <th>' . __("Rank", "vamwp") . '</th>
        <th>' . __("Rank Image", "vamwp") . '</th>
        <th>' . __("Rank Minimum", "vamwp") . '</th>
        <th>' . __("Rank Maximum", "vamwp") . '</th>
        <th>' . __("Salary Per Hour", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->rank_data as $rank) {

 			echo('<tr>
 				<td>' . $rank['rank'] . '</td>
 				<td><img height="50px" src="' . $rank['image_url'] . '" alt="" /></td>
 				<td>' . $rank['minimum_hours'] . '</td>
 				<td>' . $rank['maximum_hours'] . '</td>
 				<td>' . $rank['salary_hour'] . '</td>
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
 	register_widget( 'VAM_Widget_Ranks' );
 });

/**
* VAM_Widget_Awards: Displays complete list of all awards
*/
 class VAM_Widget_Awards extends WP_Widget {

   public function get_awards() {

 		$data = array();
 		$count = 0;

 		$sql = "select * from awards";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['award_name'] = $row['award_name'];
 			$data[$count]['award_image'] = $row['award_image'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_awards',
 			'description' => 'VAM Awards',
 		);
 		parent::__construct( 'vam_widget_awards', 'VAM Awards', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->award_data = $this->get_awards();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-awards">
 			<thead>
 				<tr>
        <th>' . __("Award Name", "vamwp") . '</th>
        <th>' . __("Award Description", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->award_data as $award) {

 			echo('<tr>
 				<td>' . $award['award_name'] . '</td>
 				<td><img src="' . $rank['award_image'] . '" alt="" /></td>
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
 	register_widget( 'VAM_Widget_Awards' );
 });

/**
* VAM_Widget_Hubs: Displays complete list of all hubs
*/
 class VAM_Widget_Hubs extends WP_Widget {

   public function get_hubs() {

 		$data = array();
 		$count = 0;

 		$sql = "select * from hubs h inner join airports a on a.ident = h.hub";

 		if (!$result = $this->vam->db->query($sql)) {
 			die('There was an error running the query [' . $this->vam->db->error . ']');
 		}

 		while ($row = $result->fetch_assoc()) {
 			$data[$count] = array();

 			$data[$count]['hub_id'] = $row['hub_id'];
 			$data[$count]['hub_code'] = $row['hub'];
 			$data[$count]['hub_name'] = $row['name'];
 			$data[$count]['hub_url'] = $row['web'];
 			$data[$count]['hub_image'] = $row['image_url'];
 			$data[$count]['hub_country'] = $row['iso_country'];

 			$count++;
 		}

 		return $data;

 	}

 	/**
 	 * Sets up the widgets name etc
 	 */
 	public function __construct() {
 		$widget_ops = array(
 			'classname' => 'vam_widget_hubs',
 			'description' => 'VAM Hubs',
 		);
 		parent::__construct( 'vam_widget_hubs', 'VAM Hubs', $widget_ops );
 		$this->vam = new VAMWP_VAM();
 		$this->hub_data = $this->get_hubs();
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

 		//$data = $this->vam->get_va_data();

 		echo ( '<table class="vam-datatable display" id="vam-hubs">
 			<thead>
 				<tr>
        <th>' . __("Hub", "vamwp") . '</th>
        <th>' . __("Number of Pilots", "vamwp") . '</th>
        <th>' . __("Number of Aircraft", "vamwp") . '</th>
        <th>' . __("Number of Routes", "vamwp") . '</th>
        <th>' . __("Info", "vamwp") . '</th>
 				</tr>
 			<thead>
 			<tbody>');

 		foreach ($this->hub_data as $hub) {

 			$hub_details = $this->vam->get_hub_details($hub['hub_id']);

 			echo('<tr>
 				<td>' . $this->vam->get_flag_icon($hub['hub_country']) . '<a
 					href="' . $this->vam->get_vamwp_url("hub_detail", $hub['hub_id']) . '">' .
 					$hub['hub_code'] . '</a><span class="vam-airportname">' . $hub['hub_name'] . '</span></td>
 				<td>' . $hub_details['num_pilots'] . '</td>
 				<td>' . $hub_details['num_fleet'] . '</td>
 				<td>' . $hub_details['num_routes'] . '</td>
 				<td><a href="' . $this->vam->get_vamwp_url("hub_detail", $hub['hub_id']) . '">'. $this->vam->get_info_icon() . '</td>
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
 	register_widget( 'VAM_Widget_Hubs' );
 });

?>

<?php

/**
 * The core VAMwp plugin class.
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

class VAMWP_VAM {

	public function __construct() {

		wp_enqueue_script('datatablejs', '//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'));
		wp_enqueue_script('datatablePercentBarjs', '//cdn.datatables.net/plug-ins/1.10.15/dataRender/percentageBars.js', array('jquery'));
		wp_enqueue_style('datatablecss', '//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');

		$this->ready = 0;

		if (get_option("mt_vam_mysql_server") != "") {
			$this->db = new mysqli(
				get_option("mt_vam_mysql_server"),
				get_option("mt_vam_mysql_username"),
				get_option("mt_vam_mysql_password"),
				get_option("mt_vam_mysql_db")
			);
			$this->db->set_charset("utf8");
			if ($this->db->connect_errno > 0) {
				//die('Unable to connect to database [' . $this->db->connect_error . ']');
			} else {

				$this->ready = 1;

				//  Get va parameters
				$sql = "select * from va_parameters ";
				if (!$result = $this->db->query($sql)) {
					die('There was an error running the query [' . $this->db->error . ']');
				}
				while ($row = $result->fetch_assoc()) {
					$this->ivao = $row["ivao"];
					$this->vatsim = $row["vatsim"];
					$this->plane_status_hangar = $row["plane_status_hangar"];
					$this->landing_crash = $row["landing_crash"];
					$this->landing_penalty1 = $row["landing_penalty1"];
					$this->landing_penalty2 = $row["landing_penalty2"];
					$this->landing_penalty3 = $row["landing_penalty3"];
					$this->landing_vs_penalty1 = $row["landing_vs_penalty1"];
					$this->landing_vs_penalty2 = $row["landing_vs_penalty2"];
					$this->flight_wear = $row["flight_wear"];
					$this->hangar_maintenance_days = $row["hangar_maintenance_days"];
					$this->hangar_crash_days = $row["hangar_crash_days"];
					$this->pilot_crash_penalty = $row["pilot_crash_penalty"];
					$this->pilot_public = $row["pilot_public"];
					$this->va_date_format = $row["date_format"];
					$this->va_time_format = $row["time_format"];
					$this->auto_approval = $row["auto_approval"];
				}

				// Base VAM URL Path
				//$this->base_url_path = "/vam/index.php";
				//$this->vam_url_path = "/vam/";
				$this->vam_url_path = get_option("mt_vam_url_path");
				$this->base_url_path = $this->vam_url_path . "index.php";

				// URL Types
				$this->url_param_name["pilot_details"] = "pilot_id";
				$this->url_param_name["airport_info"] = "airport";
				$this->url_param_name["plane_info_public"] = "registry_id";
				$this->url_param_name["hub"] = "hub_id";
				$this->url_param_name["tour_detail"] = "tour_id";
				$this->url_param_name["hub_detail"] = "hub_id";
				$this->url_rel_path["hub_detail"] = "/home/operations/hubs";

			}

		}

	}

	public function vam_auth($user,$username,$password) {

	    // Make sure a username and password are present for us to work with
    	if($username == '' || $password == '') return;

		$sql = "select gvauser_id, callsign, email, name as firstname, surname as lastname
				from gvausers where callsign='" . $username . "' and password='" . md5($password) . "'";
		if (!$result = $this->db->query($sql)) {
			die('There was an error running the query [' . $this->db->error . ']');
		}

		if ($result->num_rows != 1) {
	        $user = new WP_Error( 'denied', __("ERROR: User/pass bad") );
	     } else if( $result->num_rows == 1 ) {
	     	 $firstname = "";
	     	 $lastname = "";
	     	 $email = "";
	     	 $callsign = "";

	     	 while ($row = $result->fetch_assoc()) {
				$firstname = $row["firstname"];
				$lastname = $row["lastname"];
				$email = $row["email"];
				$callsign = $row["callsign"];
			}

	         // External user exists, try to load the user info from the WordPress user table
    	     $userobj = new WP_User();
        	 $user = $userobj->get_data_by( 'login', $callsign ); // Does not return a WP_User object 🙁
         	 $user = new WP_User($user->ID); // Attempt to load up the user with that ID

	         if( $user->ID == 0 ) {
        	     // Setup the minimum required user information for this example
            	 $userdata = array( 'user_email' => $email,
                	                'user_login' => $callsign,
                    	            'first_name' => $firstname,
                        	        'last_name' => $lastname,
                        	        'user_pass' => $password
                            	    );
	             $new_user_id = wp_insert_user( $userdata ); // A new user has been created

	             // Load the new user info
	             $user = new WP_User ($new_user_id);
    	     } else {
    	     	$userdata = array( 'ID' => $user->ID,
    	     					   'user_pass' => $password,
    	     					   'user_email' => $email,
    	     					   'first_name' => $firstname,
    	     					   'last_name' => $lastname,
    	     					   'user_login' => $callsign
    	     					   );
    	     	$update_user = wp_update_user ( $userdata );
    	     	$user = new WP_User($user->ID);
    	     }

     	}

	    return $user;
	}

	public function get_string($text) {

		return (function_exists('pll__')) ? pll__($text) : $text;

	}

	public function convertTime($dec,$format) {
		if ($format>0) {
		        return $dec;
		} else {
		        // start by converting to seconds
		        $seconds = ($dec * 3600);
		        // we're given hours, so let's get those the easy way
		        $hours = floor($dec);
		        // since we've "calculated" hours, let's remove them from the seconds variable
		        $seconds -= $hours * 3600;
		        // calculate minutes left
		        $minutes = floor($seconds / 60);
		        // remove those from seconds as well
		        //$seconds -= $minutes * 60;
		        // return the time formatted HH:MM:SS
		        return ((strlen($hours) < 2) ? "0{$hours}" : $hours) . ":" .
							((strlen($minutes) < 2) ? "0{$minutes}" : $minutes);


						//lz($hours).":".lz($minutes);
		}
	}

	// lz = leading zero
	public function lz($num) {
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}

	public function get_va_parameters() {

		$va_parameters = array();


		return $va_parameters;

	}

	public function get_vam_url($urltype, $urlparamater) {

		$url_redir = get_option("mt_vam_url_" . $urltype);
		$url_connector = "?";
		$url_param = "";

		if ($url_redir == "") {
			$url_redir = $this->base_url_path . "?page=" . $urltype;
			$url_connector = "&";
		}

		if ($this->url_param_name[$urltype]) {
			$url_param = $url_connector . $this->url_param_name[$urltype] . "=" . $urlparamater;
		}

		return $url_redir . $url_param;

	}

	public function get_vamwp_url($urltype, $urlparamater) {

		return $this->url_rel_path[$urltype] . "/" . $urltype . "?" . $this->url_param_name[$urltype] . "=" . $urlparamater;

	}

	public function get_departure_icon() {

		return '<img
				src="' . $this->vam_url_path . 'images/icons/ic_flight_takeoff_black_18dp_2x.png"
				width="20" height="20" border=0 alt="" />&nbsp;';

	}

	public function get_arrival_icon() {

		return '<img
				src="' . $this->vam_url_path . 'images/icons/ic_flight_land_black_18dp_2x.png"
				width="20" height="20" border=0 alt="" />&nbsp;';

	}

	public function get_info_icon() {

		return '<img
				src="' . $this->vam_url_path . 'images/icons/ic_info_outline_black_24dp_1x.png"
				width="20" height="20" border=0 alt="" />&nbsp;';

	}

	public function get_flag_icon($country) {

		return '<img
				src="' . $this->vam_url_path . 'images/country-flags/' . $country . '.png"
				width="25" height="20" border=0 alt="" />&nbsp;';

	}

	public function get_pilot_status_icon($status) {

		return '<img
				src="' . $this->vam_url_path . 'images/' .
				(($status == 1) ? "green" : "red") . '-user-icon.png"
				height="25" width="25" />';

	}

	public function get_plane_icaos($route_id) {

		$plane_icaos = '';

		$sql = 'select ft.plane_icao from fleettypes_routes fr, routes r, fleettypes ft where
				r.route_id=' . $route_id . ' and r.route_id=fr.route_id and fr.fleettype_id=ft.fleettype_id ';

		if (!$result = $this->db->query($sql)) {
			die('There was an error running the query [' . $this->db->error . ']');
		}

		while ($row = $result->fetch_assoc()) {
			$plane_icaos = $plane_icaos . ' ' . $row["plane_icao"];
		}

		return $plane_icaos;

	}

	public function get_pilot_callsign($pilot_id) {

		$callsign = '';

		//$sql = 'select vam_callsign from vamapi_user_map where vam_id=' . $pilot_id . '';
		$sql = 'select callsign from gvausers where gvauser_id=' . $pilot_id . '';

		if (!$result = $this->db->query($sql)) {
			die('There was an error running the query [' . $this->db->error . ']');
		}

		while ($row = $result->fetch_assoc()) {
			$callsign = $row["callsign"];
		}

		return $callsign;

	}

}


?>

<?php

/**
 * Load all widget classes.
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

// if (get_option("mt_vam_mysql_server") != "") {
  $this->vam = new VAMWP_VAM();
  if ($this->vam->ready == 1) {

  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vam/widgets/class-vamwp-widgets-airline.php';
  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vam/widgets/class-vamwp-widgets-airport.php';
  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vam/widgets/class-vamwp-widgets-aircraft.php';
  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vam/widgets/class-vamwp-widgets-hub.php';
  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vam/widgets/class-vamwp-widgets-pilot.php';

}

?>

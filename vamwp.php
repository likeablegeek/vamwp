<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/likeablegeek/vamwp
 * @since             1.0.0
 * @package           VAMWwp
 *
 * @wordpress-plugin
 * Plugin Name:       VAM Wordpress Plugin
 * Plugin URI:        https://github.com/likeablegeek/vamwp
 * Description:       VAMwp is a Wordpress plugin that provides integration with a Virtual Airlines Manager (VAM) installation on the same server.
 * Version:           1.0.0
 * Text Domain:       vamwp
 * Domain Path:       /languages
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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_vamwp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vamwp-activator.php';
	VAMWP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_vamwp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vamwp-deactivator.php';
	VAMWP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vamwp' );
register_deactivation_hook( __FILE__, 'deactivate_vamwp' );

/**
 * The core plugin class.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vamwp.php';

/**
 * Begins execution of the plugin.
 */
function run_vamwp() {

	$plugin = new VAMWP();
	$plugin->run();

}
run_vamwp();

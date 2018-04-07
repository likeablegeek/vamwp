<?php

/**
 * The admin-specific functionality of the plugin.
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

 class VAMWP_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'vamwp_admin_menu' ));

	}

	public function vamwp_admin_menu() {
		add_options_page(
			'VAMwp Options',
			'VAMwp',
			'manage_options',
			'vamwp_options',
			array(
				$this,
				'vamwp_options'
			)
		);
	}

	public function vamwp_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'vamwp' ) );
		}

		// variables for the field and option names
    $hidden_field_name = 'mt_submit_hidden';

    // Read in existing option value from database
    $mt_vam_url_path = get_option( "mt_vam_url_path" );
		$mt_vam_mysql_server = get_option("mt_vam_mysql_server");
		$mt_vam_mysql_db = get_option("mt_vam_mysql_db");
		$mt_vam_mysql_username = get_option("mt_vam_mysql_username");
		$mt_vam_mysql_password = get_option("mt_vam_mysql_password");
		$mt_vam_url_school = get_option("mt_vam_url_school");
		$mt_vam_url_stats = get_option("mt_vam_url_stats");
		$mt_vam_url_pilots_public = get_option("mt_vam_url_pilots_public");
		$mt_vam_url_va_global_financial_reports = get_option("mt_vam_url_va_global_financial_reports");
		$mt_vam_url_tours = get_option("mt_vam_url_tours");
		$mt_vam_url_staff = get_option("mt_vam_url_staff");
		$mt_vam_url_rules = get_option("mt_vam_url_rules");
		$mt_vam_url_fleet_public = get_option("mt_vam_url_fleet_public");
		$mt_vam_url_route_public = get_option("mt_vam_url_route_public");
		$mt_vam_url_plane_info_public = get_option("mt_vam_url_plane_info_public");
		$mt_vam_url_airport_info = get_option("mt_vam_url_airport_info");
		$mt_vam_url_pilot_details = get_option("mt_vam_url_pilot_details");
		$mt_vam_url_hub = get_option("mt_vam_url_hub");
		$mt_vam_url_hubs = get_option("mt_vam_url_hubs");
		$mt_vam_url_ranks = get_option("mt_vam_url_ranks");
		$mt_vam_url_awards = get_option("mt_vam_url_awards");
		$mt_vam_url_pilot_register = get_option("mt_vam_url_pilot_register");
    $mt_vam_googlemaps_api_key = get_option("mt_vam_googlemaps_api_key");

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

        $mt_vam_url_path = $_POST[ "mt_vam_url_path" ];
				update_option("mt_vam_url_path", $mt_vam_url_path);

				$mt_vam_mysql_server = $_POST[ "mt_vam_mysql_server" ];
				update_option("mt_vam_mysql_server", $mt_vam_mysql_server);

				$mt_vam_mysql_db = $_POST[ "mt_vam_mysql_db" ];
				update_option("mt_vam_mysql_db", $mt_vam_mysql_db);

				$mt_vam_mysql_username = $_POST[ "mt_vam_mysql_username" ];
				update_option("mt_vam_mysql_username", $mt_vam_mysql_username);

				$mt_vam_mysql_password = $_POST[ "mt_vam_mysql_password" ];
				update_option("mt_vam_mysql_password", $mt_vam_mysql_password);

				$mt_vam_url_school = $_POST[ "mt_vam_url_school" ];
				update_option("mt_vam_url_school", $mt_vam_url_school);

				$mt_vam_url_stats = $_POST[ "mt_vam_url_stats" ];
				update_option("mt_vam_url_stats", $mt_vam_url_stats);

				$mt_vam_url_pilots_public = $_POST[ "mt_vam_url_pilots_public" ];
				update_option("mt_vam_url_pilots_public", $mt_vam_url_pilots_public);

				$mt_vam_url_va_global_financial_reports = $_POST[ "mt_vam_url_va_global_financial_reports" ];
				update_option("mt_vam_url_va_global_financial_reports", $mt_vam_url_va_global_financial_reports);

				$mt_vam_url_tours = $_POST[ "mt_vam_url_tours" ];
				update_option("mt_vam_url_tours", $mt_vam_url_tours);

				$mt_vam_url_staff = $_POST[ "mt_vam_url_staff" ];
				update_option("mt_vam_url_staff", $mt_vam_url_staff);

				$mt_vam_url_rules = $_POST[ "mt_vam_url_rules" ];
				update_option("mt_vam_url_rules", $mt_vam_url_rules);

				$mt_vam_url_fleet_public = $_POST[ "mt_vam_url_fleet_public" ];
				update_option("mt_vam_url_fleet_public", $mt_vam_url_fleet_public);

				$mt_vam_url_route_public = $_POST[ "mt_vam_url_route_public" ];
				update_option("mt_vam_url_route_public", $mt_vam_url_route_public);

				$mt_vam_url_plane_info_public = $_POST[ "mt_vam_url_plane_info_public" ];
				update_option("mt_vam_url_plane_info_public", $mt_vam_url_plane_info_public);

				$mt_vam_url_airport_info = $_POST[ "mt_vam_url_airport_info" ];
				update_option("mt_vam_url_airport_info", $mt_vam_url_airport_info);

				$mt_vam_url_pilot_details = $_POST[ "mt_vam_url_pilot_details" ];
				update_option("mt_vam_url_pilot_details", $mt_vam_url_pilot_details);

				$mt_vam_url_hub = $_POST[ "mt_vam_url_hub" ];
				update_option("mt_vam_url_hub", $mt_vam_url_hub);

				$mt_vam_url_hubs = $_POST[ "mt_vam_url_hubs" ];
				update_option("mt_vam_url_hubs", $mt_vam_url_hubs);

				$mt_vam_url_ranks = $_POST[ "mt_vam_url_ranks" ];
				update_option("mt_vam_url_ranks", $mt_vam_url_ranks);

				$mt_vam_url_awards = $_POST[ "mt_vam_url_awards" ];
				update_option("mt_vam_url_awards", $mt_vam_url_awards);

				$mt_vam_url_pilot_register = $_POST[ "mt_vam_url_pilot_register" ];
				update_option("mt_vam_url_pilot_register", $mt_vam_url_pilot_register);

        $mt_vam_googlemaps_api_key = $_POST[ "mt_vam_googlemaps_api_key" ];
				update_option("mt_vam_googlemaps_api_key", $mt_vam_googlemaps_api_key);

        // Put a "settings saved" message on the screen
				?>
				<div class="updated"><p><strong><?php _e('Your settings have been saved. Happy flying.', 'vamwp' ); ?></strong></p></div>
				<?php

		}
		?>
		<div class="wrap">

		<h2><?php _e("VAMwp Options", "vamwp"); ?></h2>

		<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

		<h3>VAM Location</h3>

		<p>
			<input type="text" name="mt_vam_url_path" value="<?php echo $mt_vam_url_path; ?>" size="20">
			<?php _e("VAM URL Path", "vamwp"); ?>
			<br />
			<em>This is the root-relative path for the VAM portal relative to the root of the Wordpress site -- typically "/vam/".</em>
		</p>

		<hr />

		<h3>VAM Database</h3>

		<p>
			<input type="text" name="mt_vam_mysql_server" value="<?php echo $mt_vam_mysql_server; ?>" size="20">
			<?php _e("VAM MySQL Server", "vamwp"); ?>
			<br />
			<em>If your MySQL database is on the same server as VAM and Wordpress, this is likely "localhost".</em>
		</p>

		<p>
			<input type="text" name="mt_vam_mysql_db" value="<?php echo $mt_vam_mysql_db; ?>" size="20">
			<?php _e("VAM MySQL DB Name", "vamwp"); ?>
			<br />
			<em>This is the name of your VAM database and not your Wordpress database.</em>
		</p>

		<p>
			<input type="text" name="mt_vam_mysql_username" value="<?php echo $mt_vam_mysql_username; ?>" size="20">
			<?php _e("VAM MySQL DB Username", "vamwp"); ?>
			<br />
			<em>This is the username for accessing your VAM database and not your Wordpress database.</em>
		</p>

		<p>
			<input type="password" name="mt_vam_mysql_password" value="<?php echo $mt_vam_mysql_password; ?>" size="20">
			<?php _e("VAM MySQL DB Password", "vamwp"); ?>
			<br />
			<em>This is the password for accessing your VAM database and not your Wordpress database.</em>
		</p>

		<hr />

		<h3>VAM URLs</h3>
		<p><strong>Use these settings to provide alternate Wordpress page URLs to replace links to standard VAM URLs (indicated after each field).</strong></p>

		<p>
			<input type="text" name="mt_vam_url_stats" value="<?php echo $mt_vam_url_stats; ?>" size="20">
			<?php _e("Statistics Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=stats</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_pilots_public" value="<?php echo $mt_vam_url_pilots_public; ?>" size="20">
			<?php _e("Pilot Roster Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=pilots_public</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_pilot_details" value="<?php echo $mt_vam_url_pilot_details; ?>" size="20">
			<?php _e("Pilot Roster > Pilot Details Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=pilot_details</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_staff" value="<?php echo $mt_vam_url_staff; ?>" size="20">
			<?php _e("About > Staff Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=staff</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_school" value="<?php echo $mt_vam_url_school; ?>" size="20">
			<?php _e("About > School Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=school</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_rules" value="<?php echo $mt_vam_url_rules; ?>" size="20">
			<?php _e("About > Rules Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=rules</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_pilot_register" value="<?php echo $mt_vam_url_pilot_register; ?>" size="20">
			<?php _e("About > Register Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=pilot_register</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_fleet_public" value="<?php echo $mt_vam_url_fleet_public; ?>" size="20">
			<?php _e("Operations > Fleet Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=fleet_public</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_plane_info_public" value="<?php echo $mt_vam_url_plane_info_public; ?>" size="20">
			<?php _e("Operations > Fleet > Aircraft Details Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=plane_info_public</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_route_public" value="<?php echo $mt_vam_url_route_public; ?>" size="20">
			<?php _e("Operations > Routes Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=route_public</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_airport_info" value="<?php echo $mt_vam_url_airport_info; ?>" size="20">
			<?php _e("Operations > Routes > Airport Information Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=airport_info</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_hubs" value="<?php echo $mt_vam_url_hubs; ?>" size="20">
			<?php _e("Operations > Hubs Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=hubs</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_hub" value="<?php echo $mt_vam_url_hub; ?>" size="20">
			<?php _e("Operations > Hubs > Hub Details Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=hub</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_tours" value="<?php echo $mt_vam_url_tours; ?>" size="20">
			<?php _e("Operations > Tours Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=tours</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_ranks" value="<?php echo $mt_vam_url_ranks; ?>" size="20">
			<?php _e("Operations > Pilot Ranks Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=ranks</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_awards" value="<?php echo $mt_vam_url_awards; ?>" size="20">
			<?php _e("Operations > Awards Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=awards</em>
		</p>

		<p>
			<input type="text" name="mt_vam_url_va_global_financial_reports" value="<?php echo $mt_vam_url_va_global_financial_reports; ?>" size="20">
			<?php _e("Operations > Financial Report Page Page", "vamwp"); ?>
			<br />
			<em>/vam/index.php?page=va_global_financial_report</em>
		</p>

		<hr />

    <h3>Google Maps</h3>

    <p>
			<input type="text" name="mt_vam_googlemaps_api_key" value="<?php echo $mt_vam_googlemaps_api_key; ?>" size="20">
			<?php _e("Google Maps API Key", "vamwp"); ?>
			<br />
			<em>Google Maps API Key for your site</em>
		</p>

		<hr />

		<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'vamwp') ?>" />
		</p>

		</form>
		</div>
		<?php
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vamwp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vamwp-admin.js', array( 'jquery' ), $this->version, false );

	}

}

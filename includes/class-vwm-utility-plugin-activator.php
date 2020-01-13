<?php

/**
 * Fired during plugin activation
 *
 * @link       www.virtuswebmedia.com
 * @since      1.0.0
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/includes
 * @author     Nick Courage <nick@virtuswebmedia.com>
 */
class Vwm_Utility_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wp_rewrite; 

		//Write the rule
		$wp_rewrite->set_permalink_structure('/%postname%/'); 

		//Set the option
		update_option( "rewrite_rules", FALSE ); 

		//Flush the rules and tell it to write htaccess
		$wp_rewrite->flush_rules( true );

	}

}

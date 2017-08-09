<?php
/**
 * Plugin Name: Premium Link Cloaker Lite
 * Plugin URI: https://wordpress.org/plugins/premium-link-cloaker-lite/
 * Description: <strong>### <a href="http://premiumlinkcloaker.com">Get Premium Link Cloaker</a> ###</strong> The lite version of Premium link cloaker, link cloaker plugin designed and developed for affiliate marketers. 100% Newbie friendly.
 * Version: 1.0
 * Author: Yudhistira Mauris
 * Author URI: https://www.yudhistiramauris.com/
 * Text Domain: premium-link-cloaker-lite
 * Domain Path: languages
 *
 * Copyright © 2016 Yudhistira Mauris (email: mauris@yudhistiramauris.com)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if class Premium_Link_Cloaker_Lite already exists
if ( ! class_exists( 'Premium_Link_Cloaker_Lite' ) ) :

/**
* Main Premium_Link_Cloaker_Lite class
*
* This main class is responsible for instantiating the class, including the necessary files
* used throughout the plugin, and loading the plugin translation files.
*
* @since 1.0
*/
final class Premium_Link_Cloaker_Lite {

	/**
	 * The one and only true Premium_Link_Cloaker_Lite instance
	 *
	 * @since 1.0
	 * @access private
	 * @var object $instance
	 */
	private static $instance;

	/**
	 * Instantiate the main class
	 *
	 * This function instantiates the class, initialize all functions and return the object.
	 * 
	 * @since 1.0
	 * @return object The one and only true Premium_Link_Cloaker_Lite instance.
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ( ! self::$instance instanceof Premium_Link_Cloaker_Lite ) ) {

			global $plcl_settings;
			$plcl_settings = get_option( 'plcl_settings', array() );

			self::$instance = new Premium_Link_Cloaker_Lite();
			self::$instance->setup_constants();
			
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
			
			self::$instance->link     = new PLCL_Link();
			self::$instance->category = new PLCL_Category();
			self::$instance->click    = new PLCL_Click();
			if ( is_admin() ) {
				self::$instance->settings = new PLCL_Settings();
			}
		}

		return self::$instance;
	}	

	/**
	 * Function for setting up constants
	 *
	 * This function is used to set up constants used throughout the plugin.
	 *
	 * @since 1.0
	 */
	public function setup_constants() {

		// Plugin version
		if ( ! defined( 'PLCL_VERSION' ) ) {
			define( 'PLCL_VERSION', '1.0' );
		}

		// Plugin file
		if ( ! defined( 'PLCL_FILE' ) ) {
			define( 'PLCL_FILE', __FILE__ );
		}		

		// Plugin folder path
		if ( ! defined( 'PLCL_PLUGIN_PATH' ) ) {
			define( 'PLCL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		}

		// Plugin folder URL
		if ( ! defined( 'PLCL_PLUGIN_URL' ) ) {
			define( 'PLCL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin item name
		if ( ! defined( 'PLCL_ITEM_NAME' ) ) {
			define( 'PLCL_ITEM_NAME', 'Premium Link Cloaker Lite' );
		}
	}

	/**
	 * Load text domain used for translation
	 *
	 * This function loads mo and po files used to translate text strings used throughout the 
	 * plugin.
	 *
	 * @since 1.0
	 */
	public function load_textdomain() {

		// Set filter for plugin language directory
		$lang_dir = dirname( plugin_basename( PLCL_FILE ) ) . '/languages/';
		$lang_dir = apply_filters( 'plcl_languages_directory', $lang_dir );

		// Load plugin translation file
		load_plugin_textdomain( 'premium-link-cloaker-lite', false, $lang_dir );
	}

	/**
	 * Includes all necessary PHP files
	 *
	 * This function is responsible for including all necessary PHP files.
	 *
	 * @since  1.0
	 */
	public function includes() {		
		include PLCL_PLUGIN_PATH . 'includes/class-db.php';
		include PLCL_PLUGIN_PATH . 'includes/class-link.php';
		include PLCL_PLUGIN_PATH . 'includes/class-category.php';
		include PLCL_PLUGIN_PATH . 'includes/class-click.php';

		if ( is_admin() ) {
			include PLCL_PLUGIN_PATH . 'includes/admin/settings/class-settings.php';
		}
	}
}
endif; // End if class_exist check

/**
 * The main function for returning Premium_Link_Cloaker_Lite instance
 *
 * @since 1.0
 * @return object The one and only true Premium_Link_Cloaker_Lite instance.
 */
function premium_link_cloaker_lite() {
	return Premium_Link_Cloaker_Lite::instance();
}

// Run plugin
premium_link_cloaker_lite();
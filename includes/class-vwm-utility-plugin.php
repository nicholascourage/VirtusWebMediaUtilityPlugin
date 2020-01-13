<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.virtuswebmedia.com
 * @since      1.0.0
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/includes
 * @author     Nick Courage <nick@virtuswebmedia.com>
 */
class Vwm_Utility_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Vwm_Utility_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'VWM_UTILITY_PLUGIN_VERSION' ) ) {
			$this->version = VWM_UTILITY_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'vwm-utility-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Vwm_Utility_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Vwm_Utility_Plugin_i18n. Defines internationalization functionality.
	 * - Vwm_Utility_Plugin_Admin. Defines all hooks for the admin area.
	 * - Vwm_Utility_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vwm-utility-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vwm-utility-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vwm-utility-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-vwm-utility-plugin-public.php';

		if ( !class_exists( 'ReduxFramework' ) && file_exists( plugin_dir_path( __DIR__ ) . 'vendor/optionpanel/framework.php' ) ) {
		
		    require_once ( plugin_dir_path( __DIR__ ) . '/vendor/optionpanel/framework.php' );
		 
		}
		
		if ( !isset( $redux_demo ) && file_exists( plugin_dir_path( __DIR__ ) . '/vendor/optionpanel/config.php' ) ) {

			require_once ( plugin_dir_path( __DIR__ ) . '/vendor/optionpanel/config.php' );
		 
		}

		if ( file_exists( plugin_dir_path( __DIR__ ) . '/vendor/one-click-demo-import/one-click-demo-import.php' ) ) {

			require_once ( plugin_dir_path( __DIR__ ) . '/vendor/one-click-demo-import/one-click-demo-import.php' );
		 
		}


		$this->loader = new Vwm_Utility_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Vwm_Utility_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Vwm_Utility_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Vwm_Utility_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update');

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
        //write_log($plugin_basename);
		
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Set One Click Demo Importer configurations

		$this->loader->add_filter( 'pt-ocdi/import_files', $plugin_admin, 'ocdi_import_files' );


		// Display text above the import header/thumbnails

		$this->loader->add_filter( 'pt-ocdi/plugin_intro_text', $plugin_admin, 'ocdi_plugin_intro_text' );


		// Disable Proteus Themes branding

		//$this->loader->add_filter( 'pt-ocdi/disable_pt_branding', $plugin_admin,   '__return_true') 10 ,3 );

		$this->loader->add_filter( 'pt-ocdi/disable_pt_branding', $plugin_admin, 'vwm_return_true_pt_branding' );

		// Assign Front/Home pages after import

		$this->loader->add_action( 'pt-ocdi/after_import' , $plugin_admin, 'ocdi_after_import_setup'  );

		$this->loader->add_action( 'init', $plugin_admin, 'vwm_disable_page_editor' );

		//SMTP Support

		$this->loader->add_action( 'phpmailer_init', $plugin_admin, 'vwm_send_smtp_email' );

		$this->loader->add_action( 'wp_ajax_vwm_send_test_email', $plugin_admin, 'vwm_send_test_email_callback' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Vwm_Utility_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Send VWM Contact Form
		$this->loader->add_action( 'wp', $plugin_public, 'vwm_send_contact_form' );

		// Cleanup
		$this->loader->add_action( 'init', $plugin_public, 'vwm_utility_plugin_cleanup' );

		// Generate XML Sitemap
		$this->loader->add_action( 'publish_page', $plugin_public, 'vwm_sitemap' );
		$this->loader->add_action( 'publish_post', $plugin_public, 'vwm_sitemap' );
		$this->loader->add_action( 'save_post', $plugin_public, 'vwm_sitemap' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Vwm_Utility_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

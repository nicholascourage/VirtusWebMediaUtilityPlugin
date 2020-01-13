<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.virtuswebmedia.com
 * @since      1.0.0
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/admin
 * @author     Nick Courage <nick@virtuswebmedia.com>
 */
class Vwm_Utility_Plugin_Admin {

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

		$this->vwm_utility_plugin_options = get_option( $this->plugin_name );

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
		 * defined in Vwm_Utility_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vwm_Utility_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vwm-utility-plugin-admin.css', array(), $this->version, 'all' );

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
		 * defined in Vwm_Utility_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vwm_Utility_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vwm-utility-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function ocdi_import_files() {

		return array(

			array(
					'import_file_name'           => 'Virtus Web Media',
					//'categories'                 => array( 'Category 1', 'Category 2' ),
					'import_file_url'            => plugin_dir_url( dirname( __FILE__ ) ) . 'vendor/virtus-web-media/one-click-demo-import/virtus-web-media-content-import.xml',
					//'import_widget_file_url'     => 'http://www.your_domain.com/ocdi/widgets.json',
					//'import_customizer_file_url' => 'http://www.your_domain.com/ocdi/customizer.dat',
					/*'import_redux'               => array(
						array(
							'file_url'    => 'http://www.your_domain.com/ocdi/redux.json',
							'option_name' => 'redux_option_name',
						),
					),*/
					//'import_preview_image_url'   => plugin_dir_url( dirname( __FILE__ ) ) . 'admin/img/screenshot.png',
					//'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'your-textdomain' ),
					//'preview_url'                => 'http://www.your_domain.com/my-demo-1',
			)
		);
	}

	public function ocdi_plugin_intro_text( $default_text ) {
		
		$default_text .= '<div class="ocdi__intro-text">This is a custom text added to this plugin intro text.</div>';

		return $default_text;
	
	}

	public function vwm_return_true_pt_branding(){

		return true;
	}

	public function ocdi_after_import_setup() {

		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', array(

				'primary' => $main_menu->term_id, // replace 'main-menu' here with the menu location identifier from register_nav_menu() function
			)
		);

		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );

	}

	public function vwm_disable_page_editor() {
    	
    	remove_post_type_support( 'page', 'editor' );
	
	}

	 /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        
        $plugin_screen_hook_suffix = add_options_page( __('VWM Utility Plugin and Base Options Functions Setup', $this->plugin_name ), 'VWM Utility Plugin', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page') );
    
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page() {

        include_once( 'partials/vwm-utility-plugin-admin-display.php' );

    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links( $links ) {

        return array_merge(

            array(

                'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>'
            
            ),

            $links

        );

    }


    /**
     *  Save the plugin options
     *
     *
     * @since    1.0.0
     */
    public function options_update() {

        register_setting( $this->plugin_name, $this->plugin_name, array($this, 'vwm_validate') );
    
    }

    public function vwm_validate( $input ) {

    	$options = get_option( $this->plugin_name );

    	$valid = array();

        //Cleanup
        $valid['cleanup'] = ( isset( $input['cleanup'] ) && !empty( $input['cleanup'] ) ) ? 1 : 0;

        $valid['comments_css_cleanup'] = (isset($input['comments_css_cleanup']) && !empty($input['comments_css_cleanup'])) ? 1: 0;

        $valid['gallery_css_cleanup'] = (isset($input['gallery_css_cleanup']) && !empty($input['gallery_css_cleanup'])) ? 1 : 0;

        $valid['body_class_slug'] = (isset($input['body_class_slug']) && !empty($input['body_class_slug'])) ? 1 : 0;

        $valid['prettify_search'] = (isset($input['prettify_search']) && !empty($input['prettify_search'])) ? 1 : 0;

        $valid['css_js_versions'] = (isset($input['css_js_versions']) && !empty($input['css_js_versions'])) ? 1 : 0;

        $valid['jquery_cdn'] = (isset($input['jquery_cdn']) && !empty($input['jquery_cdn'])) ? 1 : 0;
        $valid['cdn_provider'] = esc_url($input['cdn_provider']);

        $valid['hide_admin_bar'] = (isset($input['hide_admin_bar']) && !empty($input['hide_admin_bar'])) ? 1 : 0;

        $valid['write_log_fn'] = (isset($input['write_log_fn']) && !empty($input['write_log_fn'])) ? 1 : 0;

        $valid['yoast_comments_cleanup'] = (isset($input['yoast_comments_cleanup']) && !empty($input['yoast_comments_cleanup'])) ? 1 : 0;

    	//SMTP Support

    	$valid['smtp_support'] = ( isset( $input['smtp_support'] ) && !empty( $input['smtp_support'] ) ) ? 1 : 0 ;

        $valid['smtp_from_name'] = ( isset( $input['smtp_from_name'] ) && !empty( $input['smtp_from_name'] ) ) ? sanitize_text_field( $input['smtp_from_name'] ) : '';

        if ( !empty( $input['smtp_support'] ) &&  empty( $valid['smtp_from_name'] ) ) { 
            add_settings_error(

                'smtp_from_name', // Setting title

                'smtp_from_name_texterror', // Error ID

                __('Please enter a name', $this->plugin_name), // Error message

                'error' // Type of message

            );

        }

        $valid['smtp_from_email'] = ( isset( $input['smtp_from_email'] ) && !empty( $input['smtp_from_email'] ) ) ? sanitize_text_field( $input['smtp_from_email'] ) : '';

        if ( !empty( $input['smtp_support'] ) && !empty( $valid['smtp_from_email'] ) && !preg_match( '/^([a-z0-9_\.-]+\@[\da-z\.-]+\.[a-z\.]{2,6})/i', $valid['smtp_from_email']  ) ) {

            add_settings_error(

                'smtp_from_email', // Setting title

                'smtp_from_email_texterror', // Error ID

                __('Please enter a valid email address', $this->plugin_name), // Error message

                'error' // Type of message

            );
        }

        $valid['smtp_authentication'] = ( isset( $input['smtp_authentication'] ) && !empty( $input['smtp_authentication'] ) ) ? 1 : 0;

        $valid['smtp_port'] = ( isset($input['smtp_port'] ) && !empty( $input['smtp_port'] ) ) ? sanitize_text_field( $input['smtp_port'] ) : '';

        if ( !empty( $input['smtp_support'] ) && !empty( $valid['smtp_port'] ) && !preg_match( '/[\d]{2,4}/i', $valid['smtp_port']  ) ) { 

            add_settings_error(

                'smtp_port', // Setting title

                'smtp_port_texterror', // Error ID

                __('Please enter a valid port number', $this->plugin_name), // Error message

                'error' // Type of message

            );
        }


        $valid['smtp_host'] = ( isset($input['smtp_host'] ) && !empty( $input['smtp_host'] ) ) ? sanitize_text_field( $input['smtp_host'] ) : '';
            
        if ( !empty( $input['smtp_support'] ) && empty( $valid['smtp_host'] ) ) {

            add_settings_error(

                'smtp_host', // Setting title

                'smtp_host_texterror', // Error ID

                __('Please enter a smtp hostname', $this->plugin_name), // Error message

                'error' // Type of message

            );
        }


        $valid['smtp_encryption'] = sanitize_text_field( $input['smtp_encryption'] );

        $valid['smtp_authentication'] = ( isset($input['smtp_authentication'] ) && !empty( $input['smtp_authentication'] ) ) ? 1 : 0;

        $valid['smtp_username'] = ( isset( $input['smtp_username'] ) && !empty( $input['smtp_username'] ) ) ? sanitize_text_field( $input['smtp_username'] ) : '';

        if ( !empty($input['smtp_support'] ) && empty( $valid['smtp_username'] ) ) { 

            add_settings_error(

                'smtp_username', // Setting title

                'smtp_username_texterror', // Error ID

                __('Please enter a username', $this->plugin_name), // Error message

                'error' // Type of message

            );
        }

        
        $valid['smtp_password'] = ( isset( $input['smtp_password'] ) && !empty( $input['smtp_password'] ) ) ? sanitize_text_field( $input['smtp_password'] ) : '';

        if ( !empty( $input['smtp_support'] ) && empty( $valid['smtp_password'] ) ) {

            add_settings_error(

                'smtp_password', // Setting title
                'smtp_password_texterror', // Error ID
                __('Please enter a password', $this->plugin_name), // Error message
                'error' // Type of message

            );
        }

        $valid['smtp_debug'] = ( isset( $input['smtp_debug'] ) && !empty( $input['smtp_debug'] ) ) ? 1 : 0 ;


    	return $valid;

    }

    public function vwm_send_smtp_email( $phpmailer ) {

    	if( !empty( $this->vwm_utility_plugin_options['smtp_support'] ) && !empty( $this->vwm_utility_plugin_options['smtp_host']) && !empty( $this->vwm_utility_plugin_options['smtp_port'] ) ) {

    		// Define that we are sending with SMTP
    		$phpmailer->isSMTP();

    		// The hostname of the mail server
    		$phpmailer->Host = $this->vwm_utility_plugin_options['smtp_host'];

    		// SMTP port number - likely to be 25, 465 or 587
    		$phpmailer->Port = $this->vwm_utility_plugin_options['smtp_port'];

    		// Set SMTP Debug to true

            if( '1' == $this->vwm_utility_plugin_options['smtp_debug'] ) {

                $phpmailer->SMTPDebug = true;

            }
    		//$phpmailer->SMTPDebug = true;

    		if( '1' == $this->vwm_utility_plugin_options['smtp_encryption'] ) {

                // Username to use for SMTP authentication
    			$phpmailer->Username = $this->vwm_utility_plugin_options['smtp_username'];

                // Password to use for SMTP authentication
    			$phpmailer->Password = $this->vwm_utility_plugin_options['smtp_password'];

    		}

            // The encryption system to use - ssl (deprecated) or tls
    		$phpmailer->SMTPSecure = $this->vwm_utility_plugin_options['smtp_encryption'];

    		if( !empty( $this->vwm_utility_plugin_options['smtp_from_email'] ) ) {

    			$phpmailer->From = $this->vwm_utility_plugin_options['smtp_from_email'];	

    		}

    		if( !empty( $this->vwm_utility_plugin_options['smtp_from_name'] ) ) {

    			$phpmailer->FromName = $this->vwm_utility_plugin_options['smtp_from_name'];	

    		}

    	}

    }

	// Smtp ajax test email
    public function vwm_send_test_email_callback(){

        $email_addr = $_POST['email_addr'];
        $subject = 'VWM Utility Plugin: ' . __('This is a test mail from Virtus Web Media Utility Plugin', $this->plugin_name) . $email_addr;
        $message = __('This test email has been successfully sent using your SMTP settings - Congrats!', $this->plugin_name);
        
        
        // Start output buffering to grab smtp debugging output
        ob_start();

        // Send the test mail
        $result = wp_mail($email_addr, $subject, $message);
        
        // Strip out the language strings which confuse users
        //unset($phpmailer->language);
        // This property became protected in WP 3.2
        
        // Grab the smtp debugging output
        $smtp_debug = ob_get_clean();
        
        echo json_encode(array('result' => $result));
        wp_die();

    }



}

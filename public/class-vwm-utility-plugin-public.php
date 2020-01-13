<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.virtuswebmedia.com
 * @since      1.0.0
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/public
 * @author     Nick Courage <nick@virtuswebmedia.com>
 */
class Vwm_Utility_Plugin_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->vwm_utility_plugin_options = get_option($this->plugin_name);


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vwm-utility-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vwm-utility-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

	public function vwm_utility_plugin_cleanup() {

		if(!empty($this->vwm_utility_plugin_options['cleanup'])){


			remove_action( 'wp_head', 'rsd_link' );                 // RSD link
			remove_action( 'wp_head', 'feed_links_extra', 3 );            // Category feed link
			remove_action( 'wp_head', 'feed_links', 2 );                // Post and comment feed links
			remove_action( 'wp_head', 'index_rel_link' );
			remove_action( 'wp_head', 'wlwmanifest_link' );
			remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );        // Parent rel link
			remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );       // Start post rel link
			//remove_action( 'wp_head', 'rel_canonical', 10, 0 );
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Adjacent post rel link
			remove_action( 'wp_head', 'wp_generator' );               // WP Version
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );

			remove_action('wp_head','wp_oembed_add_discovery_links', 10 );
			remove_action( 'wp_head', 'wp_resource_hints', 2 );

			add_action( 'wp_print_styles', function() { 

				wp_dequeue_style( 'wp-block-library' );

			}, 100 );

		}
	}

	public function vwm_send_contact_form() {

        if( isset( $_POST['submit'] ) ) { // Submit button

        	global $response;

    		//if($type == "success") $response = "<div class='success'>{$message}</div>";
    		
    		//else $response = "<div class='error'>{$message}</div>";


    		// Response messages
			$not_human       = "Human verification incorrect.";
			$missing_content = "Please supply all information.";
			$email_invalid   = "Email Address Invalid.";
			$message_unsent  = "Message was not sent. Try Again.";
			$message_sent    = "Thanks! Your message has been sent.";

        	// User input variables

            $contact_name   	= filter_input( INPUT_POST, 'contact_name', FILTER_SANITIZE_STRING );
            $email_address      = filter_input( INPUT_POST, 'email_address', FILTER_SANITIZE_STRING | FILTER_SANITIZE_EMAIL );
            $contact_number     = filter_input( INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING );
            $organisation_name 	= filter_input( INPUT_POST, 'organisation_name', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
            //$message_subject	= filter_input( INPUT_POST, 'message_subject', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
            $message_subject	= filter_input( INPUT_POST, 'message_subject', FILTER_SANITIZE_STRING );
            $message		    = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );

            //php mailer variables

		  	$to = get_option('admin_email');
		  	$subject = $message_subject; //"Someone sent a message from ". get_bloginfo('name') . "RE: " . $message_subject;
		  	$headers = 'From: '. $email_address . "\r\n" . 'Reply-To: ' . $email_address . "\r\n";

		  	$sent = wp_mail($to, $subject, strip_tags($message), $headers);

        }
    }

    public function vwm_sitemap(){

		    $postsForSitemap = get_posts( array(

		        'numberposts' => -1,

		        'orderby'     => 'modified',

		        'post_type'   => array( 'post', 'page' ),

		        'order'       => 'DESC'
		    ) );

		    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';

		    $sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"; 

		    foreach( $postsForSitemap as $post ) {

		        setup_postdata( $post );   

		        $postdate = explode( " ", $post->post_modified );   

		        $sitemap .= "\t" . '<url>' . "\n" .
		            "\t\t" . '<loc>' . get_permalink( $post->ID ) . '</loc>' .
		            "\n\t\t" . '<lastmod>' . $postdate[0] . '</lastmod>' .
		            "\n\t\t" . '<changefreq>monthly</changefreq>' .
		            "\n\t" . '</url>' . "\n";
		    }    

		    $sitemap .= '</urlset>';     

		    $fp = fopen( ABSPATH . "sitemap.xml", 'w' );

		    fwrite( $fp, $sitemap );

		    fclose( $fp );

    }

}

<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.virtuswebmedia.com
 * @since      1.0.0
 *
 * @package    Vwm_Utility_Plugin
 * @subpackage Vwm_Utility_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<h2 class="nav-tab-wrapper">
		<a href="#clean-up" class="nav-tab nav-tab-active"><?php _e('Clean up', $this->plugin_name);?></a>
        <a href="#smtp" class="nav-tab"><?php _e('Smtp Settings', $this->plugin_name);?></a>
	</h2>
		<form method="post" name="cleanup_options" action="options.php">
		<?php
			
			// Grab all value if already set
			
			$options = get_option($this->plugin_name);
    
     	 	global $menu;

			// Cleanup
			$cleanup = $options['cleanup'];
			$comments_css_cleanup = $options['comments_css_cleanup'];
			$gallery_css_cleanup = $options['gallery_css_cleanup'];
			$body_class_slug = $options['body_class_slug'];
			$prettify_search = $options['prettify_search'];
			$css_js_versions = $options['css_js_versions'];
			$jquery_cdn = $options['jquery_cdn'];
			$cdn_provider = $options['cdn_provider'];
			$hide_admin_bar = $options['hide_admin_bar'];
			$write_log_fn = $options['write_log_fn'];
			$yoast_comments_cleanup = $options['yoast_comments_cleanup'];

			// Smtp Support
			$smtp_support = $options['smtp_support'];
			$smtp_from_name = $options['smtp_from_name'];
			$smtp_from_email = $options['smtp_from_email'];
			$smtp_port = $options['smtp_port'];
			$smtp_host = $options['smtp_host'];
			$smtp_encryption = $options['smtp_encryption'];
			$smtp_authentication = $options['smtp_authentication'];
			$smtp_username = $options['smtp_username'];
			$smtp_password = $options['smtp_password'];

			/*
			* Set up hidden fields
			*
			*/
			settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);

			require_once('vwm-utility-plugin-cleanup_settings.php');

			require_once('vwm-utility-plugin-smtp.php');
		?>

		<p class="submit">
            <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>
        </p>
    </form>
</div>
<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//register_activation_hook( __FILE__, 'ctla_activate_required_plugins' );

/**
 *
 * Load the plugin after cool-timeline-pro (and other plugins) are loaded.
 *
 * @since 1.0.0
 */


add_action( 'plugins_loaded', 'ctla_load' );


function ctla_load() {

	// Load localization file
	load_plugin_textdomain( 'cool-timeline' );

	add_action('wp_enqueue_scripts','ctl_load_required_assets');
  
	// Require the main plugin file
	require( __DIR__ . '/class-cool-timeline-addon.php' );


}	// end of ctla_load()

function ctl_load_required_assets(){
			wp_enqueue_style('ctl_flexslider_style');
			wp_enqueue_script('ctl_jquery_flexslider');
			wp_enqueue_style('clt-font-awesome');
			wp_enqueue_script('ctl_viewportchecker');
			wp_enqueue_style('ctl_animate');
			wp_enqueue_style('ctl_prettyPhoto');
			wp_enqueue_style('ctl-gfonts');
			wp_enqueue_style('ctl_default_fonts');
	        wp_enqueue_script('ctl_prettyPhoto');	
	        wp_enqueue_script('ctl_scripts');
	        wp_enqueue_style('ctl_styles');
	       
}
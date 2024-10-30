<?php
/**
* Plugin Name: Hopewiser Address Lookup
* Plugin URI: https://www.hopewiser.com
* Description: Hopewiser services integration on WordPress
* Version: 2.0.4
* Author: Hopewiser Ltd
* Author URI: https://www.hopewiser.com
**/

// Include all Hopewiser CSS and JavaScript libraries
function hpwaddrlookup_includeJSCSS() {
	// General
	wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');	

	// For Address Lookup and International Address Lookup
	wp_register_script( 'hpwaddrlookup-bootstrap', plugin_dir_url( __FILE__ ) . 'includes/js2/bootstrap.min.js', array( 'jquery' ) );

	// AutoComplete
	wp_register_script('hpwaddrlookup-autoc-jsclient', plugin_dir_url( __FILE__ ) .'includes/js2/hpw-autoc-jsclient2.min.js', array('jquery'));
	wp_register_style( 'hpwaddrlookup-autoc-jsclientcss', plugin_dir_url( __FILE__ ) . 'includes/css/hpw-autoc-jsclient2.min.css' );

	// Address Lookup	
	wp_register_script( 'hpwaddrlookup-jsclient', plugin_dir_url( __FILE__ ) . 'includes/js2/hpw-jsclient2.min.js', array( 'jquery' ) );
	wp_register_style( 'hpwaddrlookup-jsclientcss', plugin_dir_url( __FILE__ ) . 'includes/css/hpw-jsclient2.min.css' );

	// International Address Lookup	
	wp_register_script( 'hpwaddrlookup-intl-jsclient', plugin_dir_url( __FILE__ ) . 'includes/js2/hpw-intl-jsclient2.min.js', array( 'jquery' ) );
	wp_register_style( 'hpwaddrlookup-intl-jsclientcss', plugin_dir_url( __FILE__ ) . 'includes/css/hpw-intl-jsclient2.min.css' );


	// Enqueue
	global $post;
	// If on a post that has our shortcodes, use the latest JQuery
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'hpw-addrlookup') ) {
        wp_enqueue_script('jquery');
		wp_enqueue_script( 'hpwaddrlookup-jsclient', plugin_dir_url( __FILE__ ) . 'js2/hpw-jsclient2.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'hpwaddrlookup-jsclientcss', plugin_dir_url( __FILE__ ) . '../css/hpw-jsclient2.min.css' );
		wp_enqueue_script( 'hpwaddrlookup-bootstrap');
	} else if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'hpw-autocomplete') ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'hpwaddrlookup-autoc-jsclient');
	} else if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'contact-form-7') ) {
		wp_enqueue_script( 'hpwaddrlookup-autoc-jsclient');
	// Other posts and pages
    }

	// If Gravity Forms is installed and active
	if(class_exists('GFCommon')) {
		wp_enqueue_script( 'hpwaddrlookup-autoc-jsclient');
	}
	wp_enqueue_style( 'main-jquery-uicss', plugin_dir_url( __FILE__ ) . 'includes/css/jquery-ui.min.css' );

	// If WooCommerce is enabled by the user, includes all functionalities of WooCommerce
	$hpwWooOptions = get_option("HPWAddrLookup_Woo");
	if(isset($hpwWooOptions["enable"])) {
		if($hpwWooOptions["enable"] == 1 && isset($hpwWooOptions["solution"])) {
			require_once plugin_dir_path(__FILE__) . 'includes/woocommerce/hpw-woo-addresses.php';

			if($hpwWooOptions["solution"] == "AutoComplete") {
				wp_enqueue_script( 'hpwaddrlookup-autoc-jsclient');
			} else if($hpwWooOptions["solution"] == "Address Lookup") {
				wp_enqueue_script( 'hpwaddrlookup-bootstrap');
				wp_enqueue_script( 'hpwaddrlookup-jsclient');
				wp_enqueue_style( 'hpwaddrlookup-jsclientcss');
			} else if($hpwWooOptions["solution"] == "International Address Lookup") {
				wp_enqueue_script( 'hpwaddrlookup-bootstrap');
				wp_enqueue_script( 'hpwaddrlookup-intl-jsclient');
				wp_enqueue_style( 'hpwaddrlookup-intl-jsclientcss');
			}
		}
	}
}
add_action('wp_enqueue_scripts', 'hpwaddrlookup_includeJSCSS');
add_action('admin_enqueue_scripts', 'hpwaddrlookup_includeJSCSS');

// Include all Hopewiser Utility functions
require_once plugin_dir_path(__FILE__) . 'includes/hpw-functions.php';

// Include the main admin settings (print tab layout, decide which ones to display & include, etc)
require_once plugin_dir_path(__FILE__) . 'includes/hpw-admin-settings.php';


// Support adding Hopewiser shortcodes on WordPress posts and pages
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-autocomplete.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-addresslookup.php';


$hpwFormOptions = get_option("HPWAddrLookup_Forms");

// If Contact Form 7 Integration is enabled and there is Contact Form 7 plugin active
if(isset($hpwFormOptions["contactForm7"])) {
	if($hpwFormOptions["contactForm7"] == 1 && function_exists('wpcf7_enqueue_scripts')) {
		require_once plugin_dir_path(__FILE__) . 'includes/contact-form-7/contact-form-7.php';
	}
}
// If Gravity Forms Integration is enabled and there is Contact Form 7 plugin active
if(isset($hpwFormOptions["gravityForms"])) {
	if($hpwFormOptions["gravityForms"] == 1 && class_exists('GFCommon') ) {
		require_once plugin_dir_path(__FILE__) . 'includes/gravity-forms/gravity-forms.php';
	}
}

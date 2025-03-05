<?php
/**
 * Plugin Name: Menu Login
 * Plugin URI: https://www.menslaveai.com/
 * Description: Elementor widget for a WooCommerce login/register icon with an AJAX popup. When not logged in, clicking the icon displays a popup. In login mode, the user enters a username/password. In register mode, the user enters only an email. On registration the widget auto‑generates a username and password, creates the account, logs the user in, and then refreshes the My Account page if present. All styling is output in one <style> block (plus an external CSS file) with full responsive @media queries. Includes a “Pre-show Popup Demo” button (editor only) to preview the login popup in Elementor.
 * Version: 1.0.0
 * Author: Jeremy with GPT
 * Author URI: https://www.menslaveai.com/
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define constants for convenience
define( 'MENU_LOGIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MENU_LOGIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| 1) LOAD OUR AJAX CLASS
|--------------------------------------------------------------------------
*/
require_once MENU_LOGIN_PLUGIN_DIR . 'includes/class-menu-login-ajax.php';
Menu_Login_Ajax::init(); // Register AJAX actions

/*
|--------------------------------------------------------------------------
| 2) CHECK IF ELEMENTOR IS LOADED, THEN REGISTER OUR WIDGET
|--------------------------------------------------------------------------
*/
function menu_login_init() {
	// If Elementor not loaded, do nothing
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Hook into Elementor’s widget registration
	add_action( 'elementor/widgets/register', 'menu_login_register_widget' );
}
add_action( 'plugins_loaded', 'menu_login_init' );

function menu_login_register_widget( $widgets_manager ) {
	require_once MENU_LOGIN_PLUGIN_DIR . 'includes/class-menu-login-widget.php';
	$widgets_manager->register( new \Menu_Login_Widget() );
}

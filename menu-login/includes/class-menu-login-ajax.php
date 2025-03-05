<?php
/**
 * AJAX handlers for the Menu Login plugin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Menu_Login_Ajax {

	/**
	 * Hooks all AJAX actions.
	 */
	public static function init() {
		// 1) Login AJAX
		add_action( 'wp_ajax_nopriv_menu_login_ajax', [ __CLASS__, 'menu_login_ajax_handler' ] );
		
		// 2) Get current user info
		add_action( 'wp_ajax_menu_login_get_current_user_info', [ __CLASS__, 'menu_login_get_current_user_info' ] );
		
		// 3) Register AJAX
		add_action( 'wp_ajax_nopriv_menu_register_ajax', [ __CLASS__, 'menu_register_ajax_handler' ] );
	}

	/**
	 * AJAX Handler #1: Login
	 */
	public static function menu_login_ajax_handler() {
		check_ajax_referer( 'menu_login_ajax_nonce', 'security' );

		$username = isset( $_POST['username'] ) ? sanitize_user( $_POST['username'] ) : '';
		$password = isset( $_POST['password'] ) ? $_POST['password'] : '';

		if ( empty( $username ) || empty( $password ) ) {
			wp_send_json_error( [ 'message' => 'Username/password cannot be empty.' ] );
		}

		$creds = [
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => true,
		];
		$user = wp_signon( $creds, false );
		if ( is_wp_error( $user ) ) {
			wp_send_json_error( [ 'message' => 'Invalid username or password.' ] );
		}

		wp_send_json_success( [ 'message' => 'Login successful!' ] );
	}

	/**
	 * AJAX Handler #2: Get current user info (to update the icon’s HTML after login)
	 */
	public static function menu_login_get_current_user_info() {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Not logged in.' ] );
		}
		$user_id   = get_current_user_id();
		$user_data = get_userdata( $user_id );
		$username  = isset( $user_data->user_login ) ? $user_data->user_login : '';
		$display_name = ( mb_strlen( $username ) > 20 ) ? mb_substr( $username, 0, 17 ) . '...' : $username;

		$avatar_url = get_avatar_url( $user_id, [ 'default' => '404' ] );
		if ( false !== strpos( $avatar_url, '404' ) ) {
			// Fallback letter if no Gravatar
			$letter = ( ! empty( $username ) ) ? strtoupper( mb_substr( $username, 0, 1 ) ) : '?';
			$icon_html = '<div class="menu-login-icon loggedin-new">' . esc_html( $letter ) . '</div>';
		} else {
			$icon_html = '<div class="menu-login-icon loggedin-new">'
			           . get_avatar( $user_id, 96, '404', 'Avatar', [ 'class' => 'menu-login-svg-icon avatar-img' ] )
			           . '</div>';
		}
		$welcome = 'Welcome ';
		$html = $icon_html . '<div class="menu-login-messages" style="gap:4px;">'
		     .   '<span class="menu-login-message menu-login-welcome">' . esc_html( $welcome ) . '</span>'
		     .   '<span class="menu-login-message menu-login-user-name">' . esc_html( $display_name ) . '</span>'
		     . '</div>';

		wp_send_json_success( [ 'html' => $html ] );
	}

	/**
	 * AJAX Handler #3: Register (only email) -> auto-generate user/pass -> auto-login
	 */
	public static function menu_register_ajax_handler() {
		check_ajax_referer( 'menu_register_ajax_nonce', 'security' );

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		if ( empty( $email ) ) {
			wp_send_json_error( [ 'message' => 'Email is required.' ] );
		}
		if ( email_exists( $email ) ) {
			wp_send_json_error( [ 'message' => 'Email already registered.' ] );
		}

		$email_parts = explode( '@', $email );
		$username = sanitize_user( $email_parts[0] );
		if ( username_exists( $username ) ) {
			$username .= '_' . wp_rand( 1000, 9999 );
		}
		$password = wp_generate_password( 12, false );
		$user_id  = wp_create_user( $username, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( [ 'message' => 'Registration failed.' ] );
		}

		$creds = [
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => true,
		];
		$user = wp_signon( $creds, false );
		if ( is_wp_error( $user ) ) {
			wp_send_json_error( [ 'message' => 'Login after registration failed.' ] );
		}

		wp_send_json_success( [ 'message' => 'Registration successful!' ] );
	}
}

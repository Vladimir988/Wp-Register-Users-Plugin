<?php
/**
 * Plugin Name: Register Users
 * Description: Register Users Plugin
 * Version:     1.0
 * Author:      Vladimir
 * Author URI:  https://github.com/Vladimir988
 */

if ( !defined( 'WPINC' ) ) {
	die();
}

include dirname( __FILE__ ) . '/test-register-users-ajax-funcs.php';
include dirname( __FILE__ ) . '/test-register-users-authorization-form-widget.php';
include dirname( __FILE__ ) . '/test-register-users-registration-form-widget.php';

register_activation_hook( __FILE__, 'test_register_users_create_option' );
function test_register_users_create_option() {
	add_role( 'test_user', __( 'Test User', 'register-users' ), array( 'read' => true ) );
}

add_action( 'wp_enqueue_scripts', 'register_users_enqueue_scripts' );
function register_users_enqueue_scripts() {

	wp_enqueue_style( 'font-awesome-css', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'test-register-users-css', plugins_url( 'css/test-register-users.css', __FILE__ ) );

	wp_register_script( 'test-register-users-js', plugins_url( 'js/test-register-users.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'test-register-users-js' );
}

add_action( 'password_reset', 'register_users_after_password_reset', 10, 2 );
function register_users_after_password_reset( $user, $new_pass ) {
	$one_time_password = get_user_meta( $user->data->ID, 'one_time_password', true );
	if( $one_time_password != '' ) {
		$user = new WP_User( $user->data->ID );
		$user->remove_role( 'test_user' );
		$user->add_role( 'subscriber' );

		delete_user_meta( $user->data->ID, 'one_time_password' );
	}   
}

add_action('wp_login', 'register_users_after_wp_login');
function register_users_after_wp_login() {
  exit( wp_redirect( admin_url() ) );
}
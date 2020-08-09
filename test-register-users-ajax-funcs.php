<?php

add_action( 'wp_head', 'test_register_users_js_variables' );
function test_register_users_js_variables() {
	$variables = array (
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	echo '<script type="text/javascript">window.wp_data = ' . json_encode( $variables ) . ';</script>';
}

add_action( 'wp_ajax_test_register_users_action_callback', 'test_register_users_action_callback' );
add_action( 'wp_ajax_nopriv_test_register_users_action_callback', 'test_register_users_action_callback' );
function test_register_users_action_callback() {
	if( ! empty( $_POST ) ) {
		if( email_exists( $_POST['email'] ) === false ) {
			try {
				$guid     = get_guid_string();
				$userdata = array(
					'user_pass'       => $guid,
					'user_login'      => $_POST['email'],
					'user_email'      => $_POST['email'],
					'role'            => 'test_user',
				);
				$user_ID = wp_insert_user( $userdata );
				$user    = get_userdata( $user_ID );
				$key     = get_password_reset_key( $user );
				add_user_meta( $user_ID, 'one_time_password', $guid, true );
				$_get_params = array(
					'action' => 'rp',
					'key'    => $key,
					'login'  => $user->data->user_login
				);
				$message     = __( "<p>If this was a mistake, just ignore this email and nothing will happen.</p> \n", 'register-users' );
				$message    .= __( "<p>To reset your password, visit the following address:</p> \n", 'register-users' );
				$message    .= get_site_url() . '/wp-login.php?' . http_build_query( $_get_params );
				wp_mail( $_POST['email'], __( 'Password Reset', 'register-users' ), $message );

				if( $user_ID ) {
					exit( 'success' );
				}
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				exit;
			}
		} elseif( $secret_link['link'] == '' ) {
			exit( __( 'Something went wrong', 'register-users' ) );
		} else {
      exit( __( 'User already exist', 'register-users' ) );
		}
	}
}

function get_guid_string() {
	if (function_exists('com_create_guid') === true) {
		return trim(com_create_guid(), '{}');
	}
	return sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
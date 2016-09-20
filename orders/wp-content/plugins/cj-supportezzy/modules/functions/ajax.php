<?php
/**
 * Ajax functions
 *
 * Setup plugin functionality, scripts and other functions.
 *
 * @author      Mohit Aneja - CSSJockey.com
 * @category    Framework
 * @package     CSSJockey/Framework
 * @version     1.0
 * @since       1.0
 */

// Date timezone
$timezone_string = get_option('timezone_string');
$timezone_string = ($timezone_string == '') ? 'UTC' : $timezone_string;
date_default_timezone_set($timezone_string);

global $wpdb, $current_user;

function cjsupport_user_login_status(){
	global $wpdb, $current_user;
	if(is_user_logged_in()){
		return sha1(cjsupport_unique_string().$current_user->ID);
	}else{
		return 'invalid';
	}
}

add_action( 'wp_ajax_nopriv_cjsupport_localize', 'cjsupport_localize' );
add_action( 'wp_ajax_cjsupport_localize', 'cjsupport_localize' );
function cjsupport_localize() {
	global $wpdb, $lang, $current_user;
	cjsupport_process_emails();
	require_once('lang.php');
	echo json_encode($lang);
	die();
}

add_action( 'wp_ajax_nopriv_cjsupport_ajax', 'cjsupport_ajax' );
add_action( 'wp_ajax_cjsupport_ajax', 'cjsupport_ajax' );
function cjsupport_ajax() {
	global $wpdb;
	cjsupport_process_emails();
	$file = $_POST['callback'];
	require_once('app/'.$file.'.php');
	echo json_encode($return);
	die();
}


function cjsupport_process_emails(){
	$cjsupport_communication_setup = cjsupport_get_option('cjsupport_communication_setup');
	if($cjsupport_communication_setup == 'imap' && function_exists('imap_open')){
		$emailpipe = cjsupport_item_path('item_dir').'/modules/functions/emailpipe.php';
		require_once($emailpipe);
	}
}

add_action('init', 'cjsupport_process_emails');
<?php
global $cjsupport_item_options;

$communication_setup = cjsupport_get_option('cjsupport_communication_setup');

if($communication_setup == 'web'){
	$com_error = sprintf(__('Email piping is not enabled. <a href="%s">Click here</a> to enable email piping.', 'cjsupport'), cjsupport_callback_url('cjsupport_app_settings'));
	echo cjsupport_show_message('error', $com_error);
}

if($communication_setup == 'imap'){

	if(function_exists('imap_open')){
		if(isset($_GET['do']) && $_GET['do'] == 'add-route'){
			require_once('email-piping/add-route.php');
		}

		if(isset($_GET['do']) && $_GET['do'] == 'edit-route' && $_GET['id'] != ''){
			require_once('email-piping/edit-route.php');
		}

		if(isset($_GET['do']) && $_GET['do'] == 'delete-route' && $_GET['id'] != ''){
			require_once('email-piping/delete-route.php');
		}

		if(!isset($_GET['do'])){
			require_once('email-piping/mailboxes.php');
		}

	}else{
		$com_error = __('<srrong>Error:</srrong><br>PHP Imap extension not found on your server, please contact your hosting provider to get it installed.<br>Refresh or visit this page again once installed.', 'cjsupport');
		echo cjsupport_show_message('error', $com_error);
	}


}
<?php

global $wpdb, $current_user;
$users_array = null;
$users = $wpdb->get_results("SELECT ID, user_login, display_name, user_email FROM $wpdb->users WHERE display_name LIKE '%{$_POST['client_string']}%' OR user_login LIKE '%{$_POST['client_string']}%' OR user_email LIKE '%{$_POST['client_string']}%' ORDER BY display_name ASC");
if(!empty($users)){
	$count = -1;
	foreach ($users as $key => $user) {
		if(cjsupport_user_type($user->ID) == 'client'){
			$count++;
			$users_array[$count]['ID'] = $user->ID;
			$users_array[$count]['user_login'] = $user->user_login;
			$users_array[$count]['user_email'] = $user->user_email;
			$users_array[$count]['display_name'] = $user->display_name;
		}
	}
}

if(is_array($users_array)){
	$return['status'] = 'success';
	$return['response'] = $users_array;
}else{

	if(!cjsupport_is_email_valid($_POST['client_string'])){
		$return['status'] = 'error';
		$return['response'] = __('No client account found, specify a valid email address to create an account and ticket on client\s behalf.', 'cjsupport');
	}elseif(cjsupport_is_email_valid($_POST['client_string']) && email_exists($_POST['client_string'])){
		$return['status'] = 'error';
		$return['response'] = __('Email address already registered.', 'cjsupport');
	}elseif(cjsupport_is_email_valid($_POST['client_string']) && !email_exists($_POST['client_string'])){
		$return['status'] = 'warning';
		$return['response'] = __('A new account will be created with this email address.', 'cjsupport');
	}


}


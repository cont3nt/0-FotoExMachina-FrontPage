<?php
global $wpdb, $current_user;

$support_page = get_post(cjsupport_get_option('page_cjsupport_app'));
$user_role_type = cjsupport_user_type();
$products_array = cjsupport_products_array('slug');
$departments_array = cjsupport_departments_array('slug');


if($user_role_type == 'non-user'){

	$cjsupport_login_link = wp_login_url(get_permalink( $support_page->ID ));
	$cjsupport_register_link = wp_registration_url();
	$login_url = cjsupport_get_option('login_url');
	$register_url = cjsupport_get_option('register_url');

	$login_link = ($login_url == '') ? $cjsupport_login_link : $login_url;
	$register_link = ($register_url == '') ? $cjsupport_register_link : $register_url;

	$login_link = '<a href="'.$login_link.'">'.__('Login', 'cjsupport').'</a>';
	$register_link = '<a href="'.$register_link.'">'.__('Register', 'cjsupport').'</a>';

	$login_message_string = cjsupport_get_option('login_message');
	$login_message = str_replace('%%login_link%%', $login_link, $login_message_string);
	$login_message = str_replace('%%register_link%%', $register_link, $login_message);

	$return['token'] = cjsupport_user_login_status();
	$return['sidebar_content'] = 'login_message';
	$return['response'] = $login_message;

}

if($user_role_type == 'client'){
	$return['token'] = cjsupport_user_login_status();
	$return['sidebar_content'] = 'submit_ticket';
	$return['response']['departments'] = $departments_array;
	$return['response']['products'] = $products_array;

}

if($user_role_type == 'agent'){
	$return['token'] = cjsupport_user_login_status();
	$return['response'] = 'agent_sidebar';
}

if($user_role_type == 'admin'){
	$return['token'] = cjsupport_user_login_status();
	$return['response'] = 'admin_sidebar';
}

$random_product = array_rand($products_array);
$random_department = array_rand($departments_array);

$return['hide_departments'] = cjsupport_get_option('hide_departments');
$return['hide_products'] = cjsupport_get_option('hide_products');
$return['default_product'] = $products_array[$random_product]['id'];
$return['default_department'] = $departments_array[$random_department]['id'];
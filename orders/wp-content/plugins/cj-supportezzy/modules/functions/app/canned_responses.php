<?php
global $wpdb, $current_user;
$errors = null;

$table_canned_responses = $wpdb->prefix.'cjsupport_canned_responses';
$user_id = $current_user->ID;

$responses = $wpdb->get_results("SELECT * FROM $table_canned_responses WHERE user_id = '{$user_id}' ORDER BY response_name ASC");

if(!empty($responses)){
	$count = -1;
	$response_array = array();
	foreach ($responses as $key => $response) {
		$count++;
		$response_array[$count]['id'] = $response->id;
		$response_array[$count]['name'] = $response->response_name;
		$response_array[$count]['content'] = cjsupport_trim_text($response->response_text, 160, '...');
		$response_array[$count]['full_content'] = $response->response_text;
		$response_array[$count]['order'] = $count;
	}

	$return['status'] = 'success';
	$return['response'] = $response_array;

}else{
	$return['status'] = 'error';
	$return['response'] = __('No canned responses found.', 'cjsupport');
}
<?php
global $wpdb, $current_user;
$errors = null;

$table_canned_responses = $wpdb->prefix.'cjsupport_canned_responses';
$user_id = $_POST['user_id'];
$rname = $_POST['rname'];
$rcontent = $_POST['rcontent'];

$check_name = $wpdb->get_row("SELECT * FROM $table_canned_responses WHERE user_id = '{$user_id}' AND response_name = '{$rname}'");

if(is_null($check_name)){
	$response_data = array(
		'user_id' => $user_id,
		'response_name' => esc_html($rname),
		'response_text' => strip_tags($rcontent, cjsupport_allowed_tags()),
	);
	$insert_id = cjsupport_insert($table_canned_responses, $response_data);

	$return['status'] = 'success';
	$return['response'] = 1;

}else{
	$return['status'] = 'error';
	$return['response'] = __('Name already exist, please choose a unique name.', 'cjsupport');
}
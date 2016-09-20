<?php
global $wpdb, $current_user;
$envato_username = cjsupport_get_option('envato_username');
$envato_api = cjsupport_get_option('envato_apikey');

$errors = null;

$purchase_code = (isset($_POST['purchase_code'])) ? $_POST['purchase_code'] : 'dummy';
$item_id = $_POST['item_id'];
$item_id = str_replace('envato-', '', $item_id);

if($purchase_code == ''){
	$errors['invalid'] = __('Please specify your purchase code.', 'cjsupport');
}

$url = "http://marketplace.envato.com/api/edge/{$envato_username}/{$envato_api}/verify-purchase:{$purchase_code}.json";
$envato_response = wp_remote_get($url);
$data = json_decode($envato_response['body']);

foreach ($data as $key => $value) {
	if(isset($value->item_id) && $item_id == $value->item_id){
		$purchased = 1;
		update_user_meta($current_user->ID, 'verified_envato-'.$item_id, $_POST['purchase_code']);
	}else{
		$purchased = null;
		update_user_meta($current_user->ID, 'verified_envato-'.$item_id, 0);
		$errors['invalid'] = __('Invalid purchase code, please check and try again.', 'cjsupport');
	}
}

if(!is_null($purchased)){
	$return['status'] = 'success';
	$return['response'] = $purchased;
}else{
	$return['status'] = 'error';
	$return['response'] = implode('<br>', $errors);
}


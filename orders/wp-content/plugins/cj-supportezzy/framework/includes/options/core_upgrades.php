<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
$cjsupport_envato_username = get_option('cjsupport_envato_username');
$cjsupport_envato_api_key = get_option('cjsupport_envato_api_key');
$cjsupport_envato_purchase_code = get_option('cjsupport_envato_purchase_code');
$cjsupport_envato_item_id = get_option('cjsupport_envato_item_id');
$cjsupport_last_update = get_option('cjsupport_last_update');

// Download Url
$download_latest_version_url = 'http://marketplace.envato.com/api/edge/'.get_option('cjsupport_envato_username').'/'.get_option('cjsupport_envato_api_key').'/download-purchase:'.get_option('cjsupport_envato_purchase_code').'.json';
$process_download_url = cjsupport_string(cjsupport_callback_url('core_upgrades')).'cjsupport_action=process_download';
// Item Details Url
$item_info_url = 'http://marketplace.envato.com/api/edge/item:'.$cjsupport_envato_item_id.'.json';

if(!isset($_GET['cjsupport_action']) && $cjsupport_envato_username != ''){
	$cjsupport_check_upgrade = wp_remote_get($item_info_url);
	if(is_wp_error($cjsupport_check_upgrade)){
		echo cjsupport_show_message('error', $cjsupport_check_upgrade->get_error_message());
	}else{
		$cjsupport_check_upgrade = json_decode($cjsupport_check_upgrade['body']);
		$remote_last_update_timestamp = strtotime($cjsupport_check_upgrade->item->last_update);
		if($cjsupport_last_update == ''){
			update_option('cjsupport_last_update', time());
		}else{
			$cjsupport_last_update = get_option('cjsupport_last_update');
			if($remote_last_update_timestamp > $cjsupport_last_update){
				echo cjsupport_show_message('info', sprintf(__('<b>New version is available to download.</b> <br><a href="%s" class="bold">Click here</a> to download latest version.', 'cjsupport'), $process_download_url));
			}else{
				echo cjsupport_show_message('success', sprintf(__('<b>You are using the latest version.</b> <br>Last checked on %s', 'cjsupport'), date('M dS, Y h:i:s A', time())));
			}
		}
	}
}


if(isset($_GET['cjsupport_action']) && $_GET['cjsupport_action'] == 'process_download'){
	$download_latest_version = wp_remote_get($download_latest_version_url);
	if(is_wp_error( $download_latest_version )){
		echo cjsupport_show_message('error', $download_latest_version->get_error_message());
	}else{
		$download_response = json_decode($download_latest_version['body']);
		foreach ($download_response as $key => $value) {
			if(isset($value->download_url)){
				wp_redirect( $value->download_url );
				update_option('cjsupport_last_update', time());
			}
		}
	}
}



global $wpdb;
$cjsupport_upgrade_errors = null;
if(isset($_POST['save_envato_info'])){
	if($_POST['cjsupport_envato_username'] == ''){
		$cjsupport_upgrade_errors[] = __('Envato Username is required.', 'cjsupport');
	}elseif($_POST['cjsupport_envato_api_key'] == ''){
		$cjsupport_upgrade_errors[] = __('Envato API Key is required.', 'cjsupport');
	}elseif($_POST['cjsupport_envato_purchase_code'] == ''){
		$cjsupport_upgrade_errors[] = __('Envato Item Purchase Code is required.', 'cjsupport');
	}else{
		$url = 'http://marketplace.envato.com/api/v3/cssjockey/c2u03ax2x2iwd6hbxfxt1ixmn5sqi74w/verify-purchase:'.$_POST['cjsupport_envato_purchase_code'].'.json';
		$response = wp_remote_get($url);
		if(is_wp_error($response)){
			$cjsupport_upgrade_errors[] = $response->get_error_message();
		}else{
			$response = json_decode($response['body']);
			foreach ($response as $key => $value) {
				if(!isset($value->item_id)){
					$cjsupport_upgrade_errors[] = __('Could not verify purchase, please try again.', 'cjsupport');
				}else{
					update_option('cjsupport_envato_item_id', $value->item_id);
				}
			}
		}
	}
	if(!is_null($cjsupport_upgrade_errors)){
		echo cjsupport_show_message('error', implode('<br>', $cjsupport_upgrade_errors));
	}else{
		update_option('cjsupport_envato_username', $_POST['cjsupport_envato_username']);
		update_option('cjsupport_envato_api_key', $_POST['cjsupport_envato_api_key']);
		update_option('cjsupport_envato_purchase_code', $_POST['cjsupport_envato_purchase_code']);
	}
}

$cjsupport_form_options['envato_api_info'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'envato_info_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s ~ Verify Purchase', 'cjsupport'), cjsupport_item_info('item_name')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'cjsupport_envato_username',
		'label' => __('Envato Username', 'cjsupport'),
		'info' => __('Specify your envato username here', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('cjsupport_envato_username'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'cjsupport_envato_api_key',
		'label' => __('Envato API Key', 'cjsupport'),
		'info' => __('Specify your envato API Key here.<br>You can create or get your API Key from Envato Profile >> Settings >> API Keys.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('cjsupport_envato_api_key'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'cjsupport_envato_purchase_code',
		'label' => __('Purchase Code', 'cjsupport'),
		'info' => __('Enter your item purchase code here.<br>You can download your purchase from Envato Profile >> Downloads Page', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('cjsupport_envato_purchase_code'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => 'save_envato_info',
		'label' => __('Verify Purchase', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

echo '<form action="" method="post">';
cjsupport_admin_form_raw($cjsupport_form_options['envato_api_info']);
echo '</form>';
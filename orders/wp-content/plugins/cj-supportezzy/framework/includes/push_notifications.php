<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
#########################################################################################
# Push Notifications
#########################################################################################

$cjsupport_notification_name = sha1('cjsupport_notification_'.site_url());
$cjsupport_notification_value = get_option($cjsupport_notification_name);
$cjsupport_notification_timestamp_name = sha1('cjsupport_notification_'.site_url().'timestamp');
$cjsupport_notification_timestamp_value = get_option($cjsupport_notification_timestamp_name);

// delete_option($cjsupport_notification_name);
// delete_option($cjsupport_notification_timestamp_name);
// die();

add_action( 'wp_ajax_cjsupport_get_notifications', 'cjsupport_get_notifications' );
function cjsupport_get_notifications() {

	$cjsupport_notification_name = sha1('cjsupport_notification_'.site_url());
	$cjsupport_notification_value = get_option($cjsupport_notification_name);

	$cjsupport_notification_timestamp_name = sha1('cjsupport_notification_'.site_url().'timestamp');
	$cjsupport_notification_timestamp_value = get_option($cjsupport_notification_timestamp_name);

	$now = time();
	$check = $cjsupport_notification_timestamp_value['timestamp'];

	$cjsupport_notification_slug = cjsupport_item_info('page_slug');
	$url = 'http://api.cssjockey.com/?cj_action=get-notifications&version='.cjsupport_item_info('item_version').'&slug='.$cjsupport_notification_slug;
	$cjsupport_notifications = wp_remote_get( $url );
	if($cjsupport_notifications['body'] != 'none'){
		$notification_response = json_decode($cjsupport_notifications['body']);
		if($cjsupport_notification_timestamp_value['ID'] != $notification_response->ID){
			update_option($cjsupport_notification_timestamp_name, array('ID' => $notification_response->ID, 'timestamp' => strtotime('+1 day'), 'closed' => 0));
			update_option($cjsupport_notification_name, $notification_response);
		}
	}

	die();
}

add_action( 'wp_ajax_cjsupport_close_notification', 'cjsupport_close_notification' );
function cjsupport_close_notification() {
	$cjsupport_notification_name = sha1('cjsupport_notification_'.site_url());
	$cjsupport_notification_value = get_option($cjsupport_notification_name);
	$cjsupport_notification_timestamp_name = sha1('cjsupport_notification_'.site_url().'timestamp');
	$cjsupport_notification_timestamp_value = get_option($cjsupport_notification_timestamp_name);
	update_option($cjsupport_notification_timestamp_name, array('ID' => $_POST['id'], 'timestamp' => strtotime('+1 day'), 'closed' => 1));
	die();
}

add_action('admin_notices' , 'cjsupport_show_notification');
function cjsupport_show_notification(){
	$cjsupport_notification_name = sha1('cjsupport_notification_'.site_url());
	$cjsupport_notification_value = get_option($cjsupport_notification_name);
	$cjsupport_notification_timestamp_name = sha1('cjsupport_notification_'.site_url().'timestamp');
	$cjsupport_notification_timestamp_value = get_option($cjsupport_notification_timestamp_name);
	if($cjsupport_notification_value && $cjsupport_notification_timestamp_value['closed'] != 1){
		$display[] = '<div id="notification-'.$cjsupport_notification_value->ID.'" class="updated push-notification-message">';
		$display[] = '<div class="notification-icon">';
		$display[] = '<img src="http://cssjockey.com/files/leaf-64.png" />';
		$display[] = '</div>';
		$display[] = '<div class="notification-content">';
		$display[] = '<h3 style="margin:0 0 10px 0;">'.cjsupport_item_info('item_name').'</h3>';
		$display[] = '<p style="font-size:14px; margin:0 0 0 0;"><b>'.$cjsupport_notification_value->title.'</b><i style="color: #999;"> ~ '.$cjsupport_notification_value->dated.'</i></p>';
		$display[] = '<div style="padding-right:50px;">'.$cjsupport_notification_value->content.'</div>';
		$display[] = '</div>';
		$display[] = '<a href="#notification-'.$cjsupport_notification_value->ID.'" data-id="'.$cjsupport_notification_value->ID.'" class="notification-close">x</a>';
		$display[] = '</div>';
		echo implode('', $display);
	}
}



function cjsupport_notifications_js(){
	$cjsupport_notification_name = sha1('cjsupport_notification_'.site_url());
	$cjsupport_notification_value = get_option($cjsupport_notification_name);
	$cjsupport_notification_timestamp_name = sha1('cjsupport_notification_'.site_url().'timestamp');
	$cjsupport_notification_timestamp_value = get_option($cjsupport_notification_timestamp_name);

	if(!isset($cjsupport_notification_timestamp_value['timestamp'])){
		update_option($cjsupport_notification_timestamp_name, array('ID' => 0, 'timestamp' => time('+1 minute'), 'closed' => 0));
	}

	$cjsupport_notification_value = get_option($cjsupport_notification_name);
	$cjsupport_notification_timestamp_value = get_option($cjsupport_notification_timestamp_name);

	$now = time();
	$check = $cjsupport_notification_timestamp_value['timestamp'];

	if($check < $now){
		wp_enqueue_script('cj-push-notifications-js', cjsupport_item_path('admin_assets_url') .'/js/push-notifications.js', array('jquery'),'',true);
	}
}
add_action( 'admin_enqueue_scripts' , 'cjsupport_notifications_js', 10);








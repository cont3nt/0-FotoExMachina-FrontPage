<?php
/**
 * Plugin Settings
 *
 * Setup plugin functionality, scripts and other functions.
 *
 * @author      Mohit Aneja - CSSJockey.com
 * @category    Framework
 * @package     CSSJockey/Framework
 * @version     1.0
 * @since       1.0
 */

global $wpdb, $current_user;

function cjsupport_app_init(){
	require_once(sprintf('%s/templates/index.php', cjsupport_item_path('item_dir')));
}
add_action('get_header', 'cjsupport_app_init');

function cjsupport_handle_empty_support_staff(){
	$staff_members = cjsupport_get_option('support_staff');
	if(is_array($staff_members) && count($staff_members) == 1 && $staff_members[0] == 0){
		$admin_id = cjsupport_user_info(get_option('admin_email'), 'ID');
		cjsupport_update_option('support_staff', serialize(array($admin_id)));
	}
	$args = array(
		'include' => cjsupport_get_option('support_staff'),
	);
	$staff_members = get_users($args);
	foreach ($staff_members as $key => $user) {
		$uds = get_user_meta($user->ID, 'cjsupport_departments', true);
		$ups = get_user_meta($user->ID, 'cjsupport_products', true);
		if(empty($uds)){
			update_user_meta($user->ID, 'cjsupport_departments', array('all'));
		}
		if(empty($ups)){
			update_user_meta($user->ID, 'cjsupport_products', array('all'));
		}
	}
}

add_action('init', 'cjsupport_handle_empty_support_staff');

function cjsupport_register_nav_menus(){
	$nav_menus = cjsupport_item_vars('nav_menus');
	register_nav_menus($nav_menus);
}
add_action('init', 'cjsupport_register_nav_menus');

// Return user type based on the user status
function cjsupport_user_type($user_id = null){
	global $wpdb, $current_user;

	if(is_null($user_id)){
		$user_id = $current_user->ID;
		$user_original_role = cjsupport_user_role($current_user->ID);
	}else{
		$user_id = $user_id;
		$user_original_role = cjsupport_user_role($user_id);
	}

	$support_staff = cjsupport_get_option('support_staff');
	$support_staff = (is_array($support_staff)) ? $support_staff : array();

	if(is_user_logged_in()){

		if($user_original_role == 'administrator' && !in_array($user_id, $support_staff)){
			$user_role = 'admin';
		}

		if($user_original_role == 'administrator' && in_array($user_id, $support_staff)){
			$admin_as_agent = cjsupport_get_option('admin_as_agent');
			if($admin_as_agent == 'yes'){
				$user_role = 'agent';
			}else{
				$user_role = 'admin';
			}
		}

		if($user_original_role != 'administrator' && in_array($user_id, $support_staff)){
			$user_role = 'agent';
		}

		if($user_original_role != 'administrator' && !in_array($user_id, $support_staff)){
			$user_role = 'client';
		}

	}else{
		$user_role = 'non-user';
	}

	return $user_role;
}


// Return all departments
function cjsupport_departments_array($object = null){
	global $wpdb;
	$args = array(
		'hide_empty' => 0,
		'order_by' => 'name',
		'order' => 'ASC'
	);
	$terms = get_terms('cjsupport_departments', $args);
	if(!empty($terms)){
		$count = -1;

		foreach ($terms as $key => $value) {
			$count++;
			if(is_null($object)){
				$return[$value->slug] = $value->name;
			}else{
				$return[$count]['id'] = $value->slug;
				$return[$count]['name'] = $value->name;
			}
		}
		return $return;
	}else{
		return null;
	}

}

// Return all products
function cjsupport_products_array($object = null){
	global $wpdb;
	$args = array(
		'hide_empty' => 0,
		'order_by' => 'name',
		'order' => 'ASC'
	);
	$terms = get_terms('cjsupport_products', $args);
	if(!empty($terms)){
		$count = -1;
		foreach ($terms as $key => $value) {
			$count++;
			if(is_null($object)){
				$return[$value->slug] = $value->name;
			}else{
				$return[$count]['id'] = $value->slug;
				$return[$count]['name'] = $value->name;
			}
		}
		return $return;
	}else{
		return null;
	}
}

// Return all employees
function cjsupport_employees_array(){
	global $wpdb;
	$support_staff = cjsupport_get_option('support_staff');
	$departments = cjsupport_departments_array();
	$products = cjsupport_products_array();
	$count = -1;
	if(is_array($support_staff)){
		foreach ($support_staff as $key => $id) {
			$count++;
			$agent_departments = get_user_meta($id, 'cjsupport_departments', true);
			$agent_products = get_user_meta($id, 'cjsupport_products', true);

			$return[$count]['id'] = $id;
			$return[$count]['name'] = cjsupport_user_info($id, 'display_name');
			$dcount = -1;
			foreach ($agent_departments as $key => $ad) {
				if(in_array($ad, array_keys($departments))){
					$dcount++;
					$return[$count]['departments'][$dcount]['id'] = $ad;
					$return[$count]['departments'][$dcount]['name'] = $departments[$ad];
				}else{
					$return[$count]['departments'] = cjsupport_departments_array('slug');
				}
			}

			$pcount = -1;
			foreach ($agent_products as $key => $ap) {
				if(in_array($ap, array_keys($products))){
					$pcount++;
					$return[$count]['products'][$pcount]['id'] = $ap;
					$return[$count]['products'][$pcount]['name'] = $products[$ap];
				}else{
					$return[$count]['products'] = cjsupport_products_array('slug');
				}
			}
		}
	}else{
		$return = array();
	}
	return $return;
}


// Return random user_id for ticket assignment based on product and department
function cjsupport_assign_ticket($post_id, $department, $product, $agent_id = null){
	$support_staff = cjsupport_get_option('support_staff');
	// if agent has department and product then assign
	if(!is_null($agent_id)){
		update_post_meta($post_id, '_assigned_to', $agent_id);
		return $agent_id;

		$agent_departments = get_user_meta($agent_id, 'cjsupport_departments', true);
		$agent_products = get_user_meta($agent_id, 'cjsupport_products', true);
		if(in_array($department, $agent_departments) && in_array($product, $agent_products)){
			update_post_meta($post_id, '_assigned_to', $agent_id);
			return $agent_id;
		}
	}
	// if agent does not has department and product
	$user_ids = array();
	foreach ($support_staff as $key => $user_id) {
		$user_departments = get_user_meta($user_id, 'cjsupport_departments', true);
		$user_products = get_user_meta($user_id, 'cjsupport_products', true);

		if(in_array($department, $user_departments) && in_array($product, $user_products)){
			$user_ids[$user_id] = $user_id;
		}
		if(in_array($department, $user_departments) && in_array('all', $user_products)){
			$user_ids[$user_id] = $user_id;
		}
		if(in_array('all', $user_departments) && in_array($product, $user_products)){
			$user_ids[$user_id] = $user_id;
		}
		if(in_array('all', $user_departments) && in_array('all', $user_products)){
			$user_ids[$user_id] = $user_id;
		}
	}
	if(!empty($user_ids)){
		$assign_to = array_rand($user_ids);
	}else{
		$fallback_user_id = cjsupport_get_option('fallback_support_staff');
		$assign_to = $fallback_user_id;
	}
	update_post_meta($post_id, '_assigned_to', $assign_to);
	return $assign_to;
}


// Register Custom Status
function cjsupport_custom_post_status_closed() {

	$args = array(
		'label'                     => _x( 'Closed', 'Status General Name', 'cjsupport' ),
		'label_count'               => _n_noop( 'Closed (%s)',  'Closed (%s)', 'cjsupport' ),
		'public'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'exclude_from_search'       => true,
	);
	register_post_status( 'Closed', $args );

}

// Hook into the 'init' action
add_action( 'init', 'cjsupport_custom_post_status_closed', 0 );

// Generate ticket meta data
function cjsupport_ticket_meta($post_id){
	global $wpdb, $current_user;

	$post = get_post($post_id);

	$author_info = cjsupport_user_info($post->post_author);

	$user_type = cjsupport_user_type($current_user->ID);

	$comment_count = null;
	$last_reply = null;
	$assigned_to = null;
	$assigned_on = null;
	$ticket_visibility = null;

	$departments = wp_get_object_terms( $post_id, 'cjsupport_departments', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all') );
	$products = wp_get_object_terms( $post_id, 'cjsupport_products', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all') );

	if(!empty($products)){
		$product = $products[0]->name;
	}else{
		$product = null;
	}

	if(!empty($departments)){
		$department = $departments[0]->name;
	}else{
		$department = null;
	}


	if($post->comment_count == 0){
		$comment_count = __('No comments', 'cjsupport');
	}elseif($post->comment_count == 1){
		$comment_count = __('1 Comment', 'cjsupport');
	}elseif($post->comment_count > 1){
		$comment_count = sprintf(__('%d Comments', 'cjsupport'), $post->comment_count);
	}


	if($post->comment_count > 0){
		$loggedin_user_type = cjsupport_user_type($current_user->ID);
		/*if($loggedin_user_type == 'client'){
			$meta_query[] = array(
				'key' => '_internal_note',
				'value' => '',
				'compare' => 'NOT EXISTS',
			);
		}else{
			$meta_query = array();
		}*/
		$comments_args = array(
			'post_id' => $post->ID,
			'orderby' => 'comment_date',
			'order' => 'ASC',
			//'meta_query' => $meta_query,
		);
		$ticket_comments_array = get_comments( $comments_args );
		if(is_array($ticket_comments_array)){
			foreach ($ticket_comments_array as $key => $comment) {
				$last_comment = $comment->comment_date;
				$last_comment_author = $comment->comment_author;
			}
			$display_name = cjsupport_user_info($last_comment_author, 'display_name');
		}
		$last_reply = sprintf(__('<i>last comment</i> <b>%s</b> <i>from</i> <b>%s</b>', 'cjsupport'), cjsupport_time_ago(strtotime($last_comment)), $display_name);
	}

	$awaiting_response_from = get_post_meta($post->ID, '_awaiting_response_from', true);
	$support_staff = cjsupport_get_option('support_staff');
	$user_type = cjsupport_user_type();
	$assigned_to = get_post_meta($post->ID, '_assigned_to', true );

	$agent_info = cjsupport_user_info($assigned_to);

	//$awaiting_response = '<span class="label label-danger">'.__('Awaiting Response', 'cjsupport').'</span>';
	$awaiting_response = '';

	if($user_type == 'client'){
		if($awaiting_response_from == $current_user->ID){
			$awaiting_response = '';
		}else{
			$awaiting_response = '<span class="label label-danger">'.__('Awaiting Response', 'cjsupport').'</span>';
		}
	}

	if($user_type != 'client'){
		if($awaiting_response_from != $assigned_to && $assigned_to == $current_user->ID && !in_array($awaiting_response_from, $support_staff)){
			$awaiting_response = '<span class="label label-danger">'.__('Awaiting Response', 'cjsupport').'</span>';
		}
	}



	// post visibility
	$visibility = get_post_meta($post->ID, '_post_visibility', true);
	if($visibility == 'private'){
		$ticket_visibility = '<span title="'.__('Private', 'cjsupport').'"><i class="fa fa-lock"></i></span> ';
	}else{
		$ticket_visibility = '<span title="'.__('Public', 'cjsupport').'"><i class="fa fa-globe"></i></span> ';
	}

	$ticket_visibility = (cjsupport_get_option('mod_public_tickets') == 'enable') ? $ticket_visibility : '';
	$categories = sprintf(__('in <b>%s</b> for <b>%s</b>', 'cjsupport'), $department, $product);


	// ticket priority
	$ticket_priority = get_post_meta($post->ID, '_priority', true);
	if($ticket_priority != '' && cjsupport_get_option('mod_ticket_priority') == 'enable'){
		$ticket_priority_label = '<i class="fa fa-flag"></i> <b>'.cjsupport_ticket_priority_label($ticket_priority).'</b> &nbsp;';
	}else{
		$ticket_priority_label = '';
	}


	$open_status[] = $awaiting_response;
	$open_status[] = '<div class="margin-10-top visible-sm visible-xs"></div>';
	$open_status[] = $ticket_visibility;
	$open_status[] = sprintf(__('Created by: <b>%s</b>, ', 'cjsupport'), $author_info['display_name']);
	$open_status[] = sprintf(__('Assigned to: <b>%s</b>', 'cjsupport'), $agent_info['display_name']);
	$open_status[] = sprintf(__('<span class="hidden-md"><i>in</i> <b>%s</b></span> ', 'cjsupport'), $product);
	$open_status[] = '<p>';
	$open_status[] = $ticket_priority_label.'<i class="fa fa-comments"></i> <b>'.$comment_count.'</b>';
	$open_status[] = $last_reply;
	$open_status[] = '</p>';

	if($post->post_status == 'closed'){
		$closed_by_id = get_post_meta($post->ID, '_closed_by', true);
		if($closed_by_id == 0){
			$closed_by_name = __('System', 'cjsupport');
		}else{
			$closed_by_name = cjsupport_user_info($closed_by_id, 'display_name');
		}

		$closed_status[] = $ticket_visibility;
		$closed_status[] = sprintf(__('Created by: <b>%s</b>, ', 'cjsupport'), $author_info['display_name']);
		$closed_status[] = sprintf(__('Assigned to: <b>%s</b>, ', 'cjsupport'), $agent_info['display_name']);
		$closed_status[] = sprintf(__('<span class="hidden-md"><i>in</i> <b>%s</b></span> ', 'cjsupport'), $product);

		$closed_status[] = '<p>';
		$closed_status[] = '<i class="fa fa-comments"></i> <b>'.$comment_count.'</b>';
		$closed_status[] = sprintf(__('<i>closed by</i> <b>%s</b>', 'cjsupport'), $closed_by_name);
		$closed_status[] = '</p>';
	}


	if($post->post_status == 'publish'){
		return implode(' ', $open_status);
	}else{
		return implode(' ', $closed_status);
	}
}


// Return ticket info by id
function cjsupport_ticket_info($ticket_id){
	global $wpdb;
	$ticket = get_post($ticket_id);

	$ticket_attachments = null;
	$ticket_attachments_array = null;
	$ticket_attachments = get_post_meta($ticket_id, '_attachments', true);

	$attachment_link = '';
	if(is_array($ticket_attachments)){
		foreach ($ticket_attachments as $key => $link) {
			$file_info = cjsupport_file_info($link);
			if(isset($file_info)){
				if(wp_attachment_is_image($file_info->ID)){
					$ticket_attachments_array[] = array(
						'id' => $file_info->ID,
						'name' => basename($file_info->guid),
						'url' => $file_info->guid,
						'image' => 1,
					);
				}else{
					$ticket_attachments_array[] = array(
						'id' => $file_info->ID,
						'name' => basename($file_info->guid),
						'url' => cjsupport_item_path('item_url').'/download.php?download='.urlencode($file_info->guid),
						'image' => 0,
					);
				}
			}

			if(is_array($ticket_attachments_array)){
				foreach ($ticket_attachments_array as $key => $attachment) {
					$attachment_link .= '<a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($attachment['url']).'">'.$attachment['name'].'</a>';
				}
			}
		}
	}

	$ticket_uid = get_post_meta($ticket->ID, '_uid', true);

	$agent_id = get_post_meta($ticket->ID, '_assigned_to', true);
	$agent_info = cjsupport_user_info($agent_id);
	$client_info = cjsupport_user_info($ticket->post_author);

	$departments = wp_get_object_terms($ticket->ID, 'cjsupport_departments' );
	$products = wp_get_object_terms($ticket->ID, 'cjsupport_products' );

	$ticket_info_array['ID'] = $ticket->ID;
	$ticket_info_array['ticket_ID'] = $ticket->ID;
	$ticket_info_array['ticket_uid'] = $ticket_uid;
	$ticket_info_array['ticket_subject'] = $ticket->post_title;
	$ticket_info_array['ticket_comment'] = $ticket->post_content;
	$ticket_info_array['attachment_link'] = $attachment_link;
	$ticket_info_array['department_id'] = $departments[0]->term_id;
	$ticket_info_array['department_name'] = $departments[0]->name;
	$ticket_info_array['product_id'] = $products[0]->term_id;
	$ticket_info_array['product_name'] = $products[0]->name;
	$ticket_info_array['ticket_status'] = $ticket->post_status;
	$ticket_info_array['client_name'] = $client_info['display_name'];
	$ticket_info_array['agent_name'] = $agent_info['display_name'];
	$ticket_info_array['client_avatar_url'] = cjsupport_get_image_url(get_avatar($ticket->post_author, 100));
	$ticket_info_array['agent_avatar_url'] = cjsupport_get_image_url(get_avatar($agent_info['user_email'], 100));
	$ticket_info_array['submitted'] = cjsupport_time_ago(strtotime($ticket->post_date));
	$ticket_info_array['agent_info'] = $agent_info;
	$ticket_info_array['client_info'] = $client_info;

	return $ticket_info_array;
}


// Return comment info by id
function cjsupport_comment_info($comment_id){
	global $wpdb;
	$comment = get_comment($comment_id);
	$comment_info_array['comment_ID'] = $comment->comment_ID;
	$comment_info_array['post_ID'] = $comment->comment_post_ID;
	$comment_info_array['author'] = $comment->comment_author;
	$comment_info_array['author_info'] = cjsupport_user_info($comment->comment_author);
	$comment_info_array['author_email'] = $comment->comment_author_email;
	$comment_info_array['author_url'] = $comment->comment_author_url;
	$comment_info_array['author_IP'] = $comment->comment_author_IP;
	$comment_info_array['date'] = $comment->comment_date;
	$comment_info_array['date_gmt'] = $comment->comment_date_gmt;
	$comment_info_array['content'] = wpautop($comment->comment_content);
	$comment_info_array['karma'] = $comment->comment_karma;
	$comment_info_array['approved'] = $comment->comment_approved;
	$comment_info_array['agent'] = $comment->comment_agent;
	$comment_info_array['type'] = $comment->comment_type;
	$comment_info_array['parent'] = $comment->comment_parent;
	$comment_info_array['user_id'] = $comment->user_id;
	return $comment_info_array;
}

// Get verified envato product for a user
function cjsupport_get_verified_envato_products($user_id){
	global $wpdb;
	$verified_products = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key LIKE 'verified_%' AND user_id = '{$user_id}'");
	if(!empty($verified_products)){
		foreach ($verified_products as $key => $vp) {
			$product_id = str_replace('verified_', '', $vp->meta_key);
			$verified_products_array[] = $product_id;
		}
		return $verified_products_array;
	}else{
		return 0;
	}
}


// Parse ticket email messages
function cjsupport_parse_email_message($message_string, $ticket_id, $commentID = null, $faqID = null){
	global $wpdb, $current_user, $cjsupport_email_messages;
	require_once('email-messages.php');

	$ticket = cjsupport_ticket_info($ticket_id);

	$ticket_url = get_permalink(cjsupport_get_option('page_cjsupport_app')).'#/ticket/'.$ticket_id;
	//$ticket_url = wp_login_url($ticket_url);

	$cjsupport_communication_setup = cjsupport_get_option('cjsupport_communication_setup');

	// $message_string = $cjsupport_email_messages[$message_string];
	$message_string = cjsupport_get_option($message_string);


	$message_string = str_replace('%%agent_name%%', $ticket['agent_info']['display_name'], $message_string);
	$message_string = str_replace('%%client_name%%', $ticket['client_info']['display_name'], $message_string);
	$message_string = str_replace('%%product_name%%', $ticket['product_name'], $message_string);
	$message_string = str_replace('%%department_name%%', $ticket['department_name'], $message_string);
	$message_string = str_replace('%%ticket_subject%%', $ticket['ticket_subject'], $message_string);
	$message_string = str_replace('%%ticket_uid%%', $ticket['ticket_uid'], $message_string);
	$message_string = str_replace('%%ticket_comment%%', wpautop($ticket['ticket_comment']), $message_string);
	$message_string = str_replace('%%ticket_url%%', $ticket_url, $message_string);


	// Comment variables
	if(!is_null($commentID)){
		$comment = cjsupport_comment_info($commentID);
		$message_string = str_replace('%%comment_content%%', $comment['content'], $message_string);
	}

	if(!is_null($faqID)){
		$faq = get_post($faqID);
		$faq_edit_link = admin_url('post.php').'?post='.$faqID.'&action=edit';
		$message_string = str_replace('%%faq_title%%', $faq->post_title, $message_string);
		$message_string = str_replace('%%faq_content%%', wpautop($faq->post_content), $message_string);
		$message_string = str_replace('%%faq_edit_link%%', $faq_edit_link, $message_string);
	}

	return $message_string;
}

// Parse ticket email subject
function cjsupport_parse_email_subject($message_string, $ticket_id, $commentID = null, $faqID = null){
	global $wpdb, $current_user, $cjsupport_email_messages;
	require_once('email-messages.php');

	$ticket = cjsupport_ticket_info($ticket_id);

	$ticket_url = get_permalink(cjsupport_get_option('page_cjsupport_app')).'#/ticket/'.$ticket_id;
	//$ticket_url = wp_login_url($ticket_url);

	//$message_string = $cjsupport_email_messages[$message_string];
	$message_string = cjsupport_get_option($message_string);

	$message_string = str_replace('[%%ticket_uid%%]', '', $message_string);

	$message_string = str_replace('%%agent_name%%', $ticket['agent_info']['display_name'], $message_string);
	$message_string = str_replace('%%client_name%%', $ticket['client_info']['display_name'], $message_string);
	$message_string = str_replace('%%product_name%%', $ticket['product_name'], $message_string);
	$message_string = str_replace('%%department_name%%', $ticket['department_name'], $message_string);
	$message_string = str_replace('%%ticket_subject%%', $ticket['ticket_subject'], $message_string);
	$message_string = str_replace('%%ticket_uid%%', $ticket['ticket_uid'], $message_string);
	$message_string = str_replace('%%ticket_comment%%', wpautop($ticket['ticket_comment']), $message_string);
	$message_string = str_replace('%%ticket_url%%', $ticket_url, $message_string);


	// Comment variables
	if(!is_null($commentID)){
		$comment = cjsupport_comment_info($commentID);
		$message_string = str_replace('%%comment_content%%', $comment['content'], $message_string);
	}

	if(!is_null($faqID)){
		$faq = get_post($faqID);
		$faq_edit_link = admin_url('post.php').'?post='.$faqID.'&action=edit';
		$message_string = str_replace('%%faq_title%%', $faq->post_title, $message_string);
		$message_string = str_replace('%%faq_content%%', wpautop($faq->post_content), $message_string);
		$message_string = str_replace('%%faq_edit_link%%', $faq_edit_link, $message_string);
	}

	return '['.$ticket['ticket_uid'].'] '.$message_string;
}

// Generate no reply email address
function cjsupport_noreply($var, $ticket_info, $force = null){
	$str = cjsupport_get_option('company_email');
	$domain = explode('@', $str);

	if(!is_null($force)){
		if($var == 'email'){
			return 'no-reply@'.$domain[1];
		}
		if($var == 'name'){
			return cjsupport_get_option('company_name');
		}
	}

	$cjsupport_communication_setup = cjsupport_get_option('cjsupport_communication_setup');
	if($cjsupport_communication_setup == 'imap'){
		$department = get_term_by('id', $ticket_info['department_id'], 'cjsupport_departments' );
		$email_routes = cjsupport_get_option('cjsupport_email_routes');
		foreach ($email_routes as $key => $route) {
			if($route['departments'] == $department->slug){
				if($var == 'email'){
					return $key;
				}
			}
			if($route['departments'] == 'all'){
				if($var == 'email'){
					return $key;
				}
			}
		}
		if($var == 'name'){
			return cjsupport_get_option('company_name');
		}
	}

	if($cjsupport_communication_setup != 'imap'){
		if($var == 'email'){
			return 'no-reply@'.$domain[1];
		}
		if($var == 'name'){
			return cjsupport_get_option('company_name');
		}
	}
}

function cjsupport_override_noreply_agents(){
	if(cjsupport_get_option('agent_can_reply_via_email') == 'yes'){
		return null;
	}else{
		return 'force-no-reply';
	}
}



// Show admin notifications if suppport page is not selected.
function cjsupport_admin_notices(){
	$support_page_notice = null;
	$support_page = cjsupport_get_option('page_cjsupport_app');
	$app_settings_link = cjsupport_callback_url('cjsupport_app_settings');
	if($support_page == 0 || $support_page == ''){
		$support_page_notice[] = sprintf(__('Support page is not specified. <a href="%s">Click here</a> to specify the support page for the app.', 'cjsupport'), $app_settings_link);
	}

	$support_staff = cjsupport_get_option('support_staff');
	if(empty($support_staff)){
		$choose_support_staff_link = cjsupport_callback_url('cjsupport_choose_staff');
		$support_page_notice[] = sprintf(__('You must <a href="%s">select and manage</a> support staff.', 'cjsupport'), $choose_support_staff_link);
	}

	$departments = get_terms( 'cjsupport_departments', array('hide_empty' => 0));
	if(empty($departments)){
		$departments_link = admin_url('edit-tags.php?taxonomy=cjsupport_departments&post_type=cjsupport');
		$support_page_notice[] = sprintf(__('You must specify <a href="%s">departments</a>.', 'cjsupport'), $departments_link);
	}

	$products = get_terms( 'cjsupport_products', array('hide_empty' => 0));
	if(empty($products)){
		$products_link = admin_url('edit-tags.php?taxonomy=cjsupport_products&post_type=cjsupport');
		$support_page_notice[] = sprintf(__('You must specify <a href="%s">products</a>.', 'cjsupport'), $products_link);
	}

	if(!is_null($support_page_notice)){
		$output[] = '<div class="error"><p>';
		$output[] = sprintf(__('<strong>%s</strong><br>', 'cjsupport'), cjsupport_item_info('item_name'));
		$output[] = implode('<br>', $support_page_notice);
		$output[] = '</p></div>';
		echo implode('', $output);
	}

}
add_action('admin_notices', 'cjsupport_admin_notices');


// Return ticket priority labels and response times..
function cjsupport_ticket_priority_options(){
	$ticket_priority_options['low'] = cjsupport_get_option('ticket_priority_low');
	$ticket_priority_options['normal'] = cjsupport_get_option('ticket_priority_normal');
	$ticket_priority_options['high'] = cjsupport_get_option('ticket_priority_high');
	$ticket_priority_options['urgent'] = cjsupport_get_option('ticket_priority_urgent');
	if(is_array($ticket_priority_options)){
		$count = -1;
		foreach ($ticket_priority_options as $key => $value) {
			if(is_array($value) && !empty($value)){
				$count++;
				$ticket_priority_array[$count] = array(
					'name' => $value[0],
					'value' => $value[1],
				);
			}
		}
		return $ticket_priority_array;
	}else{
		return null;
	}
}

// Return ticket priority label
function cjsupport_ticket_priority_label($priority_key){
	$ticket_priority_options['low'] = cjsupport_get_option('ticket_priority_low');
	$ticket_priority_options['normal'] = cjsupport_get_option('ticket_priority_normal');
	$ticket_priority_options['high'] = cjsupport_get_option('ticket_priority_high');
	$ticket_priority_options['urgent'] = cjsupport_get_option('ticket_priority_urgent');
	if(is_array($ticket_priority_options)){
		foreach ($ticket_priority_options as $key => $value) {
			if(is_array($value) && !empty($value)){
				$ticket_priority_array[$value[1]] = $value[0];
			}
		}
		return $ticket_priority_array[$priority_key];
	}
}

// Return single comment rating
function cjsupport_get_comment_rating($comment_id){
	global $wpdb;
	$table_ratings = $wpdb->prefix.'cjsupport_ticket_ratings';
	$rating = $wpdb->get_row("SELECT * from $table_ratings WHERE comment_id = '{$comment_id}'");
	if(!is_null($rating)){
		return intval($rating->rating);
	}
}

// Return ticket rating by comments
function cjsupport_ticket_rating($ticket_id){
	global $wpdb;
	$table_ticket_ratings = $wpdb->prefix.'cjsupport_ticket_ratings';
	$query = $wpdb->get_row("SELECT SUM(rating) as rating, SUM(total) as stotal, COUNT(total) as ctotal FROM $table_ticket_ratings WHERE ticket_id = '{$ticket_id}'");
	if($query->ctotal > 0){
		$rating = ($query->rating - $query->stotal + $query->stotal) / $query->ctotal;
	}else{
		$rating = 0;
	}
	return $rating;
}

// Get custom form fields
function cjsupport_custom_form_fields(){
	global $wpdb;
	$table_form_fields = $wpdb->prefix.'cjsupport_form_fields';
	$form_fields = $wpdb->get_results("SELECT * FROM $table_form_fields ORDER BY field_order ASC");
	if(!empty($form_fields)){
		$count = -1;
		foreach ($form_fields as $key => $field) {
			$count++;

			$required_text = ($field->field_required == 1) ? ' <span class="req">'.__('*', 'cjsupport').'</span>' : '';

			$form_fields_array[$count]['field_type'] = $field->field_type;
			$form_fields_array[$count]['field_id'] = $field->field_id;
			$form_fields_array[$count]['field_label'] = stripcslashes($field->field_label.$required_text);
			$form_fields_array[$count]['field_info'] = $field->field_info;
			$form_fields_array[$count]['field_options'] = explode("\r\n", $field->field_options);
			$form_fields_array[$count]['field_order'] = $field->field_order;
			$form_fields_array[$count]['field_required'] = $field->field_required;
		}

		return $form_fields_array;
	}else{
		return null;
	}
}

function cjsupport_allowed_tags(){
	return '<p><a><ul><li><img><strong><b><i><em><code><pre>';
}



// Runs an app within an iframe

function cjsupport_embed_app($height = '600px'){
	$app_uri = get_permalink(cjsupport_get_option('page_cjsupport_app'));
	return '<iframe src="'.$app_uri.'" frameborder="0" width="100%" height="'.$height.'"></iframe>';
}

// Update departments and products for fallback user
function cjsupport_update_fallback_staff(){
	$fallback_user_id = cjsupport_get_option('fallback_support_staff');
	update_user_meta($fallback_user_id, 'cjsupport_departments', array('all'));
	update_user_meta($fallback_user_id, 'cjsupport_products', array('all'));
}
add_action('init', 'cjsupport_update_fallback_staff');



// Auto Sync after specific time
function cjsupport_sync_imap(){
	$communication = cjsupport_get_option('cjsupport_communication_setup');
	if(function_exists('imap_open') && $communication == 'imap'){
		add_option('cjsupport_sync', strtotime('-15 minutes'));
		$now = time();
		$time_to_sync = get_option('cjsupport_sync');
		if($now > $time_to_sync){
			cjsupport_process_emails();
			update_option('cjsupport_sync', strtotime('15 minutes'));
		}
	}
}
add_action('init', 'cjsupport_sync_imap');

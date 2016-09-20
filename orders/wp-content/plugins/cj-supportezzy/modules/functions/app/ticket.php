<?php
global $wpdb, $current_user;

$errors = null;

$ticket_info = get_post($_POST['ID']);

if( $ticket_info->post_type == 'cjsupport'){

	$table_form_fields = $wpdb->prefix.'cjsupport_form_fields';
	$custom_fields = $wpdb->get_results("SELECT * FROM $table_form_fields ORDER BY field_order DESC");
	if(empty($custom_fields)){
		$custom_fields_info = null;
	}else{
		$count = -1;
		foreach ($custom_fields as $key => $cf) {
			$count++;
			$field_value = get_post_meta($ticket_info->ID, '_'.$cf->field_id, true);
			$custom_fields_info[$count]['key'] = $cf->field_label;
			$custom_fields_info[$count]['value'] = ($field_value != '') ? str_replace(',', ', ', $field_value) : __('NA', 'cjsupport');
		}
	}

	$stars = explode(',', get_post_meta($ticket_info->ID, '_starred_by', true));
	$starred = (in_array($current_user->ID, $stars)) ? 1 : 0;

	$visibility = get_post_meta($ticket_info->ID, '_post_visibility', true);
	$agent_id = get_post_meta($ticket_info->ID, '_assigned_to', true);
	$agent_info = cjsupport_user_info($agent_id);
	$agent_name = $agent_info['display_name'];
	$agent_login = $agent_info['user_login'];
	$user_type = cjsupport_user_type($current_user->ID);

	$client_info = cjsupport_user_info($ticket_info->post_author);

	$view_only = 0;

	if($user_type != 'admin' && $current_user->ID != $ticket_info->post_author && $current_user->ID != $agent_id){
		$view_only = 1;
	}
	if($user_type == 'admin'){
		$view_only = 0;
	}

	// Handle products and departments and fallbacks
	$departments = wp_get_object_terms($ticket_info->ID, 'cjsupport_departments' );
	$products = wp_get_object_terms($ticket_info->ID, 'cjsupport_products' );
	if(!empty($products)){
		$product_name = $products[0]->name;
		update_post_meta($ticket_info->ID, '_product_term_id', $products[0]->slug);
	}else{
		$product_term_id = get_post_meta($ticket_info->ID, '_product_term_id', true);
		wp_set_object_terms( $ticket_info->ID, $product_term_id, 'cjsupport_products', false );
	}

	if(!empty($departments)){
		$department_name = $departments[0]->name;
		update_post_meta($ticket_info->ID, '_department_term_id', $departments[0]->slug);
	}else{
		$department_term_id = get_post_meta($ticket_info->ID, '_department_term_id', true);
		wp_set_object_terms( $ticket_info->ID, $department_term_id, 'cjsupport_departments', false );
	}
	$departments = wp_get_object_terms($ticket_info->ID, 'cjsupport_departments' );
	$products = wp_get_object_terms($ticket_info->ID, 'cjsupport_products' );

	$product_name = $products[0]->name;
	$department_name = $departments[0]->name;

	$product_slug = $products[0]->slug;
	$department_slug = $departments[0]->slug;


	/*// Handle attachment Urls
	$attachment_link = null;
	$attachment_url = get_post_meta($ticket_info->ID, '_attachments', true);
	if($attachment_url){
		if( $attachment_url && is_serialized($attachment_url) ){
			if(is_array($attachment_url)){
				$attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br>';
				foreach (unserialize($attachment_url) as $key => $link) {
					$attachment_link .= '<a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($link).'">'.basename($link).'</a><br>';
				}
				$attachment_link .= '</p>';
			}
		}else{
			$attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br><a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($attachment_url).'">'.basename($attachment_url).'</a></p>';
		}
	}*/



	$ticket_attachments = null;
	$ticket_attachments_array = null;
	$ticket_attachments = get_post_meta($ticket_info->ID, '_attachments', true);
	$ticket_attachments = (is_serialized($ticket_attachments)) ? unserialize($ticket_attachments) : $ticket_attachments;
	$ticket_attachments = (!is_array($ticket_attachments)) ? array($ticket_attachments) : $ticket_attachments;
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
		}
	}

	$reference_url = strip_tags(get_post_meta($ticket_info->ID, '_url', true));
	$reference_url = ($reference_url != '') ? '<p class="reference-url">'.__('Reference Url', 'cjsupport').': <a target="_blank" href="'.$reference_url.'">'.$reference_url.'</a></p>' : null;

	// Woocommerce integration
	$purchased = '';
	$purchased_text = '';
	$sync_woocommerce = cjsupport_get_option('import_woocommerce_products');
	if($sync_woocommerce == 'yes' && cjsupport_is_woocommerce_active()){
		$product_id = str_replace('wc-', '', $product_slug);
		$woocommerce_product = get_post($product_id);
		$purchased = $woocommerce_product;
		if(!is_null($woocommerce_product) && $woocommerce_product->post_type == 'product' && isset($client_info['billing_email'])){
			$billing_email = (isset($client_info['billing_email'])) ? $client_info['billing_email'] : $client_info['user_email'];
			$purchased = woocommerce_customer_bought_product( $billing_email, $client_info['ID'], $product_id);
			$purchased_text = ($purchased) ? __('<br><span class="label label-sm inline-block margin-5-top label-success">PURCHASED<span>', 'cjsupport') : __('<br><span class="label label-sm inline-block margin-5-top label-danger">NOT PURCHASED<span>', 'cjsupport');
		}
	}

	//Envato Verification
	$envato_verified_text = null;

	if(strpos($product_slug, 'nvato-') > 0){
		$envato_verified_user_meta_key = 'verified_'.$product_slug;
		if(isset($client_info[$envato_verified_user_meta_key]) && $client_info[$envato_verified_user_meta_key] != '0'){
			update_post_meta($ticket_info->ID, '_envato_verified', 1);
		}
		$envato_verified = get_post_meta($ticket_info->ID, '_envato_verified', true);
		$envato_verified_text = ($envato_verified == 1) ? __('<br><span class="label label-sm inline-block margin-5-top label-success">VERIFIED<span>', 'cjsupport') : __('<br><span class="label label-sm inline-block margin-5-top label-danger">NOT VERIFIED<span>', 'cjsupport');
	}

	$ticket_closed_by_id = intval(get_post_meta($ticket_info->ID, '_closed_by', true));
	$ticket_closed_by = ($ticket_closed_by_id != 0) ? cjsupport_user_info($ticket_closed_by_id, 'display_name') : __('System', 'cjsupport');

	// ticket priority
	$ticket_priority = get_post_meta($ticket_info->ID, '_priority', true);
	if($ticket_priority != '' && cjsupport_get_option('mod_ticket_priority') == 'enable'){
		$ticket_priority_value = $ticket_priority;
		$ticket_priority_label = cjsupport_ticket_priority_label($ticket_priority);
	}else{
		$normal_priority = cjsupport_get_option('ticket_priority_normal');
		$ticket_priority_value = $normal_priority[1];
		$ticket_priority_label = cjsupport_ticket_priority_label($normal_priority[1]);
	}

	$ticket_info_array['ID'] = $ticket_info->ID;
	$ticket_info_array['ticket_uid'] = get_post_meta($ticket_info->ID, '_uid', true);
	$ticket_info_array['ticket_status'] = $ticket_info->post_status;
	$ticket_info_array['ticket_priority'] = $ticket_priority_label;
	$ticket_info_array['ticket_priority_value'] = $ticket_priority_value;
	$ticket_info_array['ticket_closed_by_id'] = $ticket_closed_by_id;
	$ticket_info_array['ticket_status_msg'] = ($ticket_info->post_status == 'closed') ? sprintf(__('Closed by %s', 'cjsupport'), $ticket_closed_by) : __('Open', 'cjsupport');
	$ticket_info_array['user_name'] = cjsupport_user_info($ticket_info->post_author, 'display_name');
	$ticket_info_array['agent_name'] = $agent_name;
	$ticket_info_array['agent_login'] = $agent_login;
	$ticket_info_array['submitted'] = cjsupport_time_ago(strtotime($ticket_info->post_date));
	$ticket_info_array['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket_info->post_author, '125'));
	$ticket_info_array['current_user_avatar'] = cjsupport_get_image_url(get_avatar($current_user->ID, '125'));
	if(cjsupport_get_option('mod_public_tickets') == 'enable'){
		$ticket_info_array['visibility'] = $visibility;
		$ticket_info_array['visibility_button'] = ($visibility == 'private') ? __('Mark Public', 'cjsupport') : __('Mark as Private', 'cjsupport');
	}else{
		$ticket_info_array['visibility'] = null;
		$ticket_info_array['visibility_button'] = null;
	}
	$ticket_info_array['department'] = $department_name;
	$ticket_info_array['product'] = $product_name;
	$ticket_info_array['starred'] = $starred;
	$ticket_info_array['title'] = $ticket_info->post_title;
	$ticket_info_array['content'] = $reference_url.$ticket_info->post_content;
	$ticket_info_array['view_only'] = $view_only;
	$ticket_info_array['user_type'] = $user_type;
	$ticket_info_array['attachments'] = $ticket_attachments_array;

	$ticket_info_array['purchased'] = $purchased_text;
	$ticket_info_array['envato_verified'] = $envato_verified_text;
	$ticket_info_array['ticket_rating'] = cjsupport_ticket_rating($ticket_info->ID);

	$ticket_info_array['more_info'] = $custom_fields_info;


	// Return comments on this post
	$loggedin_user_type = cjsupport_user_type($current_user->ID);
	if($loggedin_user_type == 'client'){
		$meta_query[] = array(
			'key' => '_internal_note',
			'value' => '',
			'compare' => 'NOT EXISTS',
		);
	}else{
		$meta_query = array();
	}

	$comments_args = array(
		'post_id' => $ticket_info->ID,
		'orderby' => 'comment_date_gmt',
		'order' => 'ASC',
		//'meta_query' => $meta_query,
	);
	$ticket_comments_array = get_comments( $comments_args );

	$collapsed = (count($ticket_comments_array) >= 4) ? count($ticket_comments_array) - 3 : 0;
	$show_uncollapse_button = (count($ticket_comments_array) > 4) ? 1 : 0;

	$ticket_comments = array();
	$count = -1;
	foreach ($ticket_comments_array as $key => $comment) {

		$count++;
		$comment_author_info = cjsupport_user_info($comment->comment_author);

		$comment_attachments = null;
		$comment_attachments_array = null;
		$comment_attachments = get_comment_meta($comment->comment_ID, '_attachments', true);
		$comment_attachments = (is_serialized($comment_attachments)) ? unserialize($comment_attachments) : $comment_attachments;
		$comment_attachments = (!is_array($comment_attachments)) ? array($comment_attachments) : $comment_attachments;
		if($comment_attachments != '' && is_array($comment_attachments)){
			foreach ($comment_attachments as $key => $link) {
				$file_info = cjsupport_file_info($link);
				if(isset($file_info)){
					if(wp_attachment_is_image($file_info->ID)){
						$comment_attachments_array[] = array(
							'id' => $file_info->ID,
							'name' => basename($file_info->guid),
							'url' => $file_info->guid,
							'image' => 1,
						);
					}else{
						$comment_attachments_array[] = array(
							'id' => $file_info->ID,
							'name' => basename($file_info->guid),
							'url' => cjsupport_item_path('item_url').'/download.php?download='.urlencode($file_info->guid),
							'image' => 0,
						);
					}
				}
			}
		}

		$can_edit_comment = ($current_user->ID == $comment_author_info['ID']) ? 1 : 0;
		$can_create_faq = (cjsupport_user_type($comment_author_info['ID']) == 'agent' && $comment_author_info['ID'] == $current_user->ID) ? 1 : 0;

		$comment_user_type = cjsupport_user_type($comment_author_info['ID']);
		if($comment_user_type == 'client' && cjsupport_get_option('client_can_edit_comment') == 'no'){
			$can_edit_comment = 0;
		}
		if($comment_user_type == 'agent' && cjsupport_get_option('agent_can_edit_comment') == 'no'){
			$can_edit_comment = 0;
		}

		if($user_type == 'agent' && cjsupport_get_option('agent_can_create_faq') == 'no'){
			$can_create_faq = 0;
		}

		$user_type = '';
		$user_type = cjsupport_user_type($comment_author_info['ID']);
		$current_user_type = cjsupport_user_type($current_user->ID);

		if(($current_user_type == 'client' && get_comment_meta($comment->comment_ID, '_internal_note', true) == 1)){
			$ticket_comments[$count]['comment_content'] = get_comment_meta( $comment->comment_ID, '_client_notes', true );
		}else{
			$via_email = get_comment_meta( $comment->comment_ID, '_via_email', true );
			if($via_email == 1){
				$comment_content = $comment->comment_content;
			}else{
				$comment_content = $comment->comment_content;
			}
			$ticket_comments[$count]['comment_content'] = wpautop($comment_content);
		}

		$ticket_comments[$count]['comment_ID'] = $comment->comment_ID;
		$ticket_comments[$count]['comment_post_ID'] = $comment->comment_post_ID;
		$ticket_comments[$count]['comment_author'] = $comment->comment_author;
		$ticket_comments[$count]['comment_author_email'] = $comment->comment_author_email;
		$ticket_comments[$count]['comment_author_IP'] = $comment->comment_author_IP;
		$ticket_comments[$count]['comment_date'] = cjsupport_time_ago(strtotime($comment->comment_date));
		$ticket_comments[$count]['comment_date_gmt'] = cjsupport_time_ago(strtotime($comment->comment_date_gmt));
		$ticket_comments[$count]['comment_karma'] = $comment->comment_karma;
		$ticket_comments[$count]['comment_approved'] = $comment->comment_approved;
		$ticket_comments[$count]['comment_rating'] = cjsupport_get_comment_rating($comment->comment_ID);
		$ticket_comments[$count]['comment_agent'] = $comment->comment_agent;
		$ticket_comments[$count]['comment_type'] = $comment->comment_type;
		$ticket_comments[$count]['comment_parent'] = $comment->comment_parent;
		$ticket_comments[$count]['user_id'] = $comment->user_id;
		$ticket_comments[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($comment->user_id, '125'));
		$ticket_comments[$count]['display_name'] = $comment_author_info['display_name'];
		$ticket_comments[$count]['comment_user_type'] = $user_type;
		$ticket_comments[$count]['count'] = $count + 1;
		$ticket_comments[$count]['order'] = $count;
		$ticket_comments[$count]['internal_note'] = ($current_user_type != 'client' && get_comment_meta($comment->comment_ID, '_internal_note', true) == 1) ? __('<span class="red">(Internal Note)</span>', 'cjsupport') : '';
		$ticket_comments[$count]['can_edit_comment'] = $can_edit_comment;
		$ticket_comments[$count]['can_create_faq'] = $can_create_faq;
		$ticket_comments[$count]['attachments'] = $comment_attachments_array;

	}

	$return['departments'] = cjsupport_departments_array('slug');
	$return['products'] = cjsupport_products_array('slug');
	$return['agents'] = cjsupport_employees_array();

	$return['status'] = 'success';
	$return['uncollapse_button'] = $show_uncollapse_button;
	$return['collapse_count'] = $collapsed;
	$return['response'] = $ticket_info_array;
	$return['comments'] = $ticket_comments;
}else{
	$return['response'] = 'not-allowed';
}



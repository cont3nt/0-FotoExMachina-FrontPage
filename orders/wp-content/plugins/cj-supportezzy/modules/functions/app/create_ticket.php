<?php
global $wpdb, $current_user;

extract($_POST);
$errors = null;

$table_form_fields = $wpdb->prefix.'cjsupport_form_fields';
$custom_fields = $wpdb->get_results("SELECT * FROM $table_form_fields WHERE field_required = '1' ORDER BY field_order DESC");
if(!empty($custom_fields)){
	foreach ($custom_fields as $ck => $cv) {
		if(!isset($_POST[$cv->field_id])){
			$errors['missing'] = __('Missing required fields', 'cjsupport');
		}
	}
}

if(!isset($product) || !isset($department) || !isset($subject) || !isset($content)){
	$errors['missing'] = __('Missing required fields', 'cjsupport');
}


if(!is_null($errors)){
	$return['status'] = 'errors';
	$return['response'] = implode('<br>', $errors);
}else{

	$post_data = array(
		'post_content'   => (cjsupport_get_option('textarea_type') == 'wysiwyg') ? $content : nl2br($content),
		'post_name'      => sanitize_title( $subject ),
		'post_title'     => $subject,
		'post_status'    => 'publish',
		'post_type'      => 'cjsupport',
		'post_author'    => $current_user->ID,
		'ping_status'    => 'closed',
		'post_excerpt'   => cjsupport_trim_text(strip_tags($content), 160, '...'),
		'comment_status' => 'open'
	);

	$post = wp_insert_post( $post_data );
	$post = get_post($post);

	wp_set_object_terms( $post->ID, $product, 'cjsupport_products', false );
	wp_set_object_terms( $post->ID, $department, 'cjsupport_departments', false );

	$exclude_manual_meta = array(
		'_url',
		'_post_visibility',
		'_attachments',
		'_awaiting_response_from',
		'_starred_by',
		'_envato_verified',
		'_uid',
	);

	foreach ($_POST as $mkey => $mvalue) {
		if(!in_array($mkey, $exclude_manual_meta)){
			update_post_meta($post->ID, '_'.$mkey, $mvalue);
		}
	}

	if(isset($url)){
		$url = (!strpos($url, 'ttp://') && !strpos($url, 'ttps://')) ? 'http://'.$url : $url;
		update_post_meta($post->ID, '_url', strip_tags(esc_url($url)));
	}

	$default_visibility = cjsupport_get_option('ticket_visibility');
	update_post_meta($post->ID, '_post_visibility', $default_visibility);

	if(isset($attachments)){
		$attachments = explode(',', $_POST['attachments']);
		update_post_meta($post->ID, '_attachments', $attachments);
	}

	$assgined_to = cjsupport_assign_ticket($post->ID, $department, $product);
	update_post_meta($post->ID, '_awaiting_response_from', $current_user->ID);
	update_post_meta($post->ID, 'ticket_status', 'publish');
	update_post_meta($post->ID, '_starred_by', 'none');

	update_post_meta($post->ID, '_envato_verified', @$_POST['envato_verified']);

	$ticket_id = cjsupport_unique_string().$post->ID;
	update_post_meta($post->ID, '_uid', $ticket_id);


	// Send email to client
	$ticket_info = cjsupport_ticket_info($post->ID);
	$email_data = array(
	    'to' => $current_user->user_email,
	    'from_name' => cjsupport_noreply('name', $ticket_info),
	    'from_email' => cjsupport_noreply('email', $ticket_info),
	    'subject' => cjsupport_parse_email_subject('new_ticket_subject_to_client', $ticket_info['ID']),
	    'message' => cjsupport_parse_email_message('new_ticket_to_client', $ticket_info['ID']),
	);
	cjsupport_email($email_data);

	// Send email to agent
	$email_data = array(
	    'to' => $ticket_info['agent_info']['user_email'],
	    'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
	    'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
	    'subject' => cjsupport_parse_email_subject('new_ticket_subject_to_agent', $ticket_info['ID']),
	    'message' => cjsupport_parse_email_message('new_ticket_to_agent', $ticket_info['ID']),
	);
	cjsupport_email($email_data);



	$return['status'] = 'success';
	$return['response'] = $post;

}

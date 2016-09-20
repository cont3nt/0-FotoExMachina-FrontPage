<?php
global $wpdb, $current_user;

$errors = null;

if(@$_POST['title'] == ''){
	$errors[] = __('Please specify a question', 'cjsupport');
}
if(@$_POST['content'] == ''){
	$errors[] = __('Please specify an answer', 'cjsupport');
}

if(is_null($errors)){
	$role_type = cjsupport_user_type($current_user->ID);
	$faq_data = array(
		'post_content'   => $_POST['content'],
		'post_name'      => sanitize_title( $_POST['title'] ),
		'post_title'     => $_POST['title'],
		'post_status'    => ($role_type == 'admin') ? 'publish' : 'draft',
		'post_type'      => 'cjsupport_faqs',
		'post_author'    => $current_user->ID,
		'ping_status'    => 'closed',
		'post_excerpt'   => cjsupport_trim_text(strip_tags($_POST['content']), 160, '...'),
		'comment_status' => 'closed'
	);

	$faq_post_id = wp_insert_post( $faq_data );
	wp_set_object_terms( $faq_post_id, $_POST['product'], 'cjsupport_products', false );
	$message = ($role_type == 'admin') ? __('Question published successfully.', 'cjsupport') : __('Question submitted for review.', 'cjsupport');

	$ticket_id = $_POST['ticket_id'];
	$ticket_info = cjsupport_ticket_info($ticket_id);
	$email_data = array(
	    'to' => cjsupport_get_option('new_faq_email_to'),
	    'from_name' => cjsupport_noreply('name', $ticket_info, 'force-no-reply'),
	    'from_email' => cjsupport_noreply('email', $ticket_info, 'force-no-reply'),
	    'subject' => cjsupport_parse_email_subject('new_faq_subject_to_admin', $ticket_info['ID'], null, $faq_post_id),
	    'message' => cjsupport_parse_email_message('new_faq_to_admin', $ticket_info['ID'], null, $faq_post_id),
	);
	cjsupport_email($email_data);


	$return['status'] = 'success';
	$return['response'] = $message;
}else{
	$return['status'] = 'errors';
	$return['response'] = implode('<br>', $errors);
}



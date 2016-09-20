<?php
global $wpdb, $current_user;

if($_POST['act'] == 'toggle_closed'){
	$post_status = $_POST['set_ticket_status'];
	$post_data = array(
		'ID' => $_POST['ID'],
		'post_status' => $post_status
	);
	wp_update_post($post_data);
	update_post_meta($_POST['ID'], '_ticket_status', $post_status);
	if($post_status == 'closed'){
		update_post_meta($_POST['ID'], '_closed_by', $current_user->ID);
		$email_ticket_status = 'closed';
	}else{
		delete_post_meta($_POST['ID'], '_closed_by');
		$email_ticket_status = 'reopened';
	}


	$user_type = cjsupport_user_type($current_user->ID);
	if($user_type == 'agent' || $user_type == 'admin'){

		// Send email to client
		$ticket_info = cjsupport_ticket_info($_POST['ID']);
		$post_status_subject = ($post_status == 'closed') ? 'ticket_closed_subject_to_client' : 'ticket_reopened_subject_to_client';
		$post_status_msg = ($post_status == 'closed') ? 'ticket_closed_to_client' : 'ticket_reopened_to_client';
		$email_data = array(
		    'to' => $ticket_info['client_info']['user_email'],
		    'from_name' => cjsupport_noreply('name', $ticket_info),
		    'from_email' => cjsupport_noreply('email', $ticket_info),
		    'subject' => cjsupport_parse_email_subject($post_status_subject, $ticket_info['ID']),
		    'message' => cjsupport_parse_email_message($post_status_msg, $ticket_info['ID']),
		);
		cjsupport_email($email_data);

	}

	if($user_type == 'client'){

		// Send email to client
		$ticket_info = cjsupport_ticket_info($_POST['ID']);
		$post_status_subject = ($post_status == 'closed') ? 'ticket_closed_subject_to_agent' : 'ticket_reopened_subject_to_agent';
		$post_status_msg = ($post_status == 'closed') ? 'ticket_closed_to_agent' : 'ticket_reopened_to_agent';
		$email_data = array(
		    'to' => $ticket_info['agent_info']['user_email'],
		    'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
		    'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
		    'subject' => cjsupport_parse_email_subject($post_status_subject, $ticket_info['ID']),
		    'message' => cjsupport_parse_email_message($post_status_msg, $ticket_info['ID']),
		);
		cjsupport_email($email_data);

	}


	$return['status'] = 'success';
	$return['response'] = $_POST['set_ticket_status'];
}

if($_POST['act'] == 'toggle_visibility'){
	$visibility = get_post_meta($_POST['ID'], '_post_visibility', true);
	$new_visibility = ($visibility == 'private') ? 'public' : 'private';
	update_post_meta($_POST['ID'], '_post_visibility', $new_visibility);
	$return['status'] = 'success';
	$return['response'] = $visibility;
}

if($_POST['act'] == 'toggle_starred'){
	$post_id = $_POST['ID'];
	$status = $_POST['status'];
	$starred_by = get_post_meta($post_id, '_starred_by', true);

	$starred_by_array = explode(',', $starred_by);

	if($status == 'add' && !in_array($current_user->ID, $starred_by_array)){
		update_post_meta($post_id, '_starred_by', $starred_by.','.$current_user->ID);
	}

	if($status == 'remove' && in_array($current_user->ID, $starred_by_array)){
		foreach ($starred_by_array as $key => $value) {
			if($value != $current_user->ID){
				$new_starred_by_array[] = $value;
			}
		}
		$starred_by = implode(',', $new_starred_by_array);
		update_post_meta($post_id, '_starred_by', $starred_by);
	}

	$return = $starred_by;
}


if($_POST['act'] == 'toggle_priority'){
	update_post_meta($_POST['ticket_id'], '_priority', $_POST['priority']);
	$return['status'] = 'success';
	$return['response'] = $_POST['priority'];
}
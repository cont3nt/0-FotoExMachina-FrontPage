<?php
global $wpdb;

function cjsupport_auto_close_unattended_tickets(){
	global $wpdb, $current_user;
	$days = cjsupport_get_option('auto_close_ticket_days');
	$args = array(
		'posts_per_page' => 10,
		'orderby' => 'post_date',
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
	);
	$tickets = get_posts($args);
	foreach ($tickets as $key => $ticket) {
		$next_auto_close_check = get_post_meta($ticket->ID, '_next_auto_close_check', true);
		if(time() > $next_auto_close_check){
			update_post_meta($ticket->ID, '_next_auto_close_check', time() + (3600 * 24 * 2));
		}
		$next_unattended_check = get_post_meta($ticket->ID, '_next_unattended_check', true);
		if(time() > $next_unattended_check){
			update_post_meta($ticket->ID, '_next_unattended_check', time() + (3600 * 24 * 1));
		}
		if(cjsupport_user_type($ticket->post_author) == 'client'){
			$last_comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '{$ticket->ID}' ORDER BY comment_ID DESC");
			if(!is_null($last_comment)){
				$comment_author_info = cjsupport_user_info($last_comment->comment_author_email);
				if(cjsupport_user_type($comment_author_info['ID']) != 'client'){
					if($days > 0){
						$close_time = strtotime($last_comment->comment_date) + (3600 * $days);
						// Close ticket
						if(time() > $close_time && time() > $next_auto_close_check){
							$post_data = array(
								'ID' => $ticket->ID,
								'post_status' => 'closed'
							);
							wp_update_post($post_data);
							update_post_meta($ticket->ID, '_ticket_status', 'closed');
							update_post_meta($ticket->ID, '_closed_by', 0);
							if(cjsupport_get_option('auto_close_emails') == 'enable'){
								$email_data = array(
									'to' => $comment_author_info['user_email'],
									'from_name' => cjsupport_get_option('company_name'),
									'from_email' => cjsupport_get_option('company_email'),
									'subject' => cjsupport_parse_email_subject('auto_close_subject_to_client', $ticket->ID),
									'message' => cjsupport_parse_email_message('auto_close_message_to_client', $ticket->ID),
								);
								cjsupport_email($email_data);
							}
						}
					}
				}
				if(cjsupport_user_type(cjsupport_user_info($last_comment->comment_author_email, 'ID')) == 'agent'){
					$maximum_response_hours = cjsupport_get_option('maximum_response_time');
					$ticket_info = cjsupport_post_info($last_comment->comment_post_ID);
					if(isset($ticket_info['_priority']) && $ticket_info['_priority'] > 0){
						$maximum_response_hours = intval($ticket_info['_priority']);
					}
					if($maximum_response_hours > 0 && time() > $next_unattended_check){
						$agent_notification_hours = round($maximum_response_hours - ($maximum_response_hours / 3), 2);
						$admin_notification_hours = $maximum_response_hours;
						$last_comment_time = strtotime($last_comment->comment_date);
						$agent_notification_time = strtotime($last_comment->comment_date) + (3600 * $agent_notification_hours);
						$admin_notification_time = strtotime($last_comment->comment_date) + (3600 * $admin_notification_hours);
						if(time() > $agent_notification_time){
							$agent_info = cjsupport_user_info($ticket_info['_assigned_to']);
							$email_data = array(
								'to' => $agent_info['user_email'],
								'from_name' => sprintf(__('Unattended Ticket Alert @ %s', 'cjsupport'), cjsupport_get_option('company_name')),
								'from_email' => cjsupport_get_option('company_email'),
								'subject' => cjsupport_parse_email_subject('unattended_subject_to_agent', $ticket_info['ID']),
								'message' => cjsupport_parse_email_subject('unattended_message_to_agent', $ticket_info['ID']),
							);
							cjsupport_email($email_data);
						}
						if(time() > $admin_notification_time){
							$email_data = array(
								'to' => get_option('admin_email'),
								'from_name' => sprintf(__('Unattended Ticket Alert @ %s', 'cjsupport'), cjsupport_get_option('company_name')),
								'from_email' => cjsupport_get_option('company_email'),
								'subject' => cjsupport_parse_email_subject('unattended_subject_to_admin', $ticket_info['ID']),
								'message' => cjsupport_parse_email_subject('unattended_message_to_admin', $ticket_info['ID']),
							);
							cjsupport_email($email_data);
						}
					}
				}
			}
		}
	}

}

add_action('init', 'cjsupport_auto_close_unattended_tickets');
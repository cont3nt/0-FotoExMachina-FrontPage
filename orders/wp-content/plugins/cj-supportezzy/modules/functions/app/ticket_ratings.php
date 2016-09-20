<?php
global $wpdb, $current_user;

$table_ticket_ratings = $wpdb->prefix.'cjsupport_ticket_ratings';

$ticket_id = $_POST['ticket_id'];
$comment_id = $_POST['comment_id'];
$rating = $_POST['rating'];

$check_existing = $wpdb->get_row("SELECT * FROM $table_ticket_ratings WHERE ticket_id = '{$ticket_id}' AND comment_id = '{$comment_id}' AND user_id = '{$current_user->ID}'");

if(is_null($check_existing)){
	$rating_data = array(
		'user_id' => $current_user->ID,
		'ticket_id' => $ticket_id,
		'comment_id' => $comment_id,
		'rating' => $rating,
		'total' => 5,
	);
	cjsupport_insert($table_ticket_ratings, $rating_data);
	$return['status'] = 'success';
	$return['response'] = __('Thank you for your rating on this comment.', 'cjsupport');
}else{
	$rating_data = array(
		'user_id' => $current_user->ID,
		'ticket_id' => $ticket_id,
		'comment_id' => $comment_id,
		'rating' => $rating,
		'total' => 5,
	);
	cjsupport_update($table_ticket_ratings, $rating_data, 'id', $check_existing->id);
	$return['status'] = 'success';
	$return['response'] = __('Your rating has been updated for this comment.', 'cjsupport');
}
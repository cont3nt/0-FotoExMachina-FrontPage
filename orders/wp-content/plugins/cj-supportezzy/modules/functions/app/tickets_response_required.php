<?php
global $wpdb, $current_user;

extract($_POST);
$errors = null;


$user_type = cjsupport_user_type($current_user->ID);

$post_author = null;

$meta_query = null;
$meta_query[] = array(
	'key' => '_awaiting_response_from',
	'value' => $current_user->ID,
	'compare' => '!=',
);


$args = array(
	//'author' => $post_author,
	'posts_per_page' => 150,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'post_type' => 'cjsupport',
	'post_status' => 'publish',
	'meta_query' => $meta_query
);

$tickets = get_posts($args);

$return_tickets = null;

$count = -1;

$support_staff = cjsupport_get_option('support_staff');
$user_type = cjsupport_user_type();

foreach ($tickets as $key => $ticket) {
	$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
	$starred = (in_array($current_user->ID, $stars)) ? 1 : 0;
	$awaiting_response_from = get_post_meta($ticket->ID, '_awaiting_response_from', true );
	$assigned_to = get_post_meta($ticket->ID, '_assigned_to', true );

	if($user_type != 'client' && !in_array($awaiting_response_from, $support_staff) && $assigned_to == $current_user->ID){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, 100));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

	if($user_type == 'client' && in_array($awaiting_response_from, $support_staff) && $ticket->post_author == $current_user->ID){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, 100));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

}
$page_title = sprintf(__('Awaiting Response (%s)', 'cjsupport'), count($return_tickets));
$return['tickets_count'] = count($return_tickets);
$return['status'] = 'success';
$return['page_title'] = $page_title;
$return['tickets'] = $return_tickets;
$return['response'] = $_POST;















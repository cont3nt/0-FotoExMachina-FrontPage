<?php
global $wpdb, $current_user;

extract($_POST);
$errors = null;


$user_type = cjsupport_user_type($current_user->ID);

$post_author = null;

$meta_query = null;
$meta_query[] = array(
	'key' => '_post_visibility',
	'value' => 'public',
	'compare' => '=',
);



$post_author = ($user_type == 'client') ? $current_user->ID : null;

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

$page_title = sprintf(__('All Public Tickets (%s)', 'cjsupport'), count($tickets));


$return_tickets = null;

$count = -1;
foreach ($tickets as $key => $ticket) {
	$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
	$starred = (in_array($current_user->ID, $stars)) ? 1 : 0;

	$count++;
	$return_tickets[$count]['ID'] = $ticket->ID;
	$return_tickets[$count]['subject'] = $ticket->post_title;
	$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, 100));
	$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
	$return_tickets[$count]['ticket_status'] = $ticket->post_status;
	$return_tickets[$count]['starred'] = $starred;
	$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);

}


$return['tickets_count'] = count($return_tickets);
$return['status'] = 'success';
$return['page_title'] = $page_title;
$return['tickets'] = $return_tickets;
$return['response'] = $_POST;















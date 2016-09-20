<?php
global $wpdb, $current_user;

extract($_POST);
$errors = null;

$user_type = cjsupport_user_type($current_user->ID);

$post_author = null;

if($user_type == 'agent'){
	$meta_query[] = array(
		'key' => '_assigned_to',
		'value' => $current_user->ID,
		'compare' => '=',
	);
}else{
	$meta_query[] = null;
}

$get_post_author = null;
if(isset($_POST['by_user']) && $_POST['by_user'] != 'undefined'){
	$meta_query = null;
	$meta_query[] = array(
		'key' => '_assigned_to',
		'value' => $_POST['by_user'],
		'compare' => '=',
	);
	$author_check = get_posts(array('post_type' => 'cjsupport', 'meta_query' => $meta_query, 'post_status' => 'publish'));
	if(count($author_check) == 0){
		$meta_query = null;
		$get_post_author = $_POST['by_user'];
	}
}


if($_POST['post_status'] == 'publish'){
	$post_status = 'publish';
}elseif($_POST['post_status'] == 'closed'){
	$post_status = 'closed';
	$meta_query[] = array(
		'key' => '_ticket_status',
		'value' => 'closed',
		'compare' => '=',
	);
}elseif($_POST['post_status'] == 'starred'){
	$post_status = 'publish';
}

$tax_query = null;
if(isset($_POST['product_slug']) && $_POST['product_slug'] != 'undefined'){
	$tax_query[] = array(
		'taxonomy' => 'cjsupport_products',
		'field'    => 'slug',
		'terms'    => $_POST['product_slug'],
	);
}
if(isset($_POST['department_slug']) && $_POST['department_slug'] != 'undefined'){
	$tax_query[] = array(
		'taxonomy' => 'cjsupport_departments',
		'field'    => 'slug',
		'terms'    => $_POST['department_slug'],
	);
}

$post_author = ($user_type == 'client') ? $current_user->ID : $get_post_author;

$args = array(
	'author' => $post_author,
	'posts_per_page' => 150,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'post_type' => 'cjsupport',
	'post_status' => $post_status,
	'meta_query' => $meta_query,
	'tax_query' => $tax_query,
);


$tickets = get_posts($args);

if($_POST['post_status'] == 'publish'){
	$page_title = sprintf(__('All Open Tickets (%s)', 'cjsupport'), count($tickets));
}elseif($_POST['post_status'] == 'closed'){
	$page_title = sprintf(__('Closed Tickets (%s)', 'cjsupport'), count($tickets));
}elseif($_POST['post_status'] == 'starred'){
	$page_title = __('Starred Tickets', 'cjsupport');
}elseif($_POST['post_status'] == 'awaiting-response'){
	$page_title = __('Awaiting Response', 'cjsupport');
}elseif($_POST['post_status'] == 'public'){
	$page_title = __('Public Tickets', 'cjsupport');
}

if(isset($_POST['product_slug']) && $_POST['product_slug'] != 'undefined'){
	$term = get_term_by( 'slug', $_POST['product_slug'], 'cjsupport_products' );
	$page_title = sprintf(__('%s', 'cjsupport'), $term->name);
}
if(isset($_POST['department_slug']) && $_POST['department_slug'] != 'undefined'){
	$term = get_term_by( 'slug', $_POST['department_slug'], 'cjsupport_departments' );
	$page_title = sprintf(__('%s', 'cjsupport'), $term->name);
}

if(isset($_POST['by_user']) && $_POST['by_user'] != 'undefined'){
	$page_title = sprintf(__('Tickets linked to: %s', 'cjsupport'), cjsupport_user_info($_POST['by_user'], 'display_name'));
}


$return_tickets = null;

$count = -1;
foreach ($tickets as $key => $ticket) {
	$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
	$starred = (in_array($current_user->ID, $stars)) ? 1 : 0;

	if($_POST['post_status'] == 'starred' && $starred == 1){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, '125'));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

	if($_POST['post_status'] == 'publish'){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, '125'));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

	if($_POST['post_status'] == 'closed'){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, '125'));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

	if($_POST['post_status'] == 'public' && get_post_meta($ticket->ID, '_post_visibility', true) == 'public'){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, '125'));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

	$awaiting_response_from = get_post_meta($ticket->ID, '_awaiting_response_from', true);
	if($_POST['post_status'] == 'awaiting-response' && $current_user->ID == $awaiting_response_from){
		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, '125'));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);
	}

}

if(count($return_tickets) > 0){
	$return['tickets_count'] = count($return_tickets);
	$return['status'] = 'success';
	$return['page_title'] = $page_title;
	$return['tickets'] = $return_tickets;
	$return['response'] = $_POST;
}else{
	$return['tickets_count'] = count($return_tickets);
	$return['status'] = 'error';
	$return['page_title'] = __('No tickets found', 'cjsupport');
	$return['tickets'] = __('No tickets found matched your query.', 'cjsupport');
	$return['response'] = $_POST;
}







<?php
global $current_user;
$user_type = cjsupport_user_type($current_user->ID);

$open_tickets = null;
$closed_tickets = null;
$public_tickets = null;
$starred = null;

if($user_type == 'client'){

	$post_args = null;
	$post_args = array(
		'author' => $current_user->ID,
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
	);
	$open_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'author' => $current_user->ID,
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'closed',
		'meta_query' => array(
			array(
				'key' => '_ticket_status',
				'value' => 'closed',
				'compare' => '=',
			)
		),
	);
	$closed_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'author' => $current_user->ID,
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => array('publish', 'closed'),
		'meta_query' => array(
			array(
				'key' => '_post_visibility',
				'value' => 'public',
				'compare' => '=',
			)
		)
	);
	$public_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'author' => $current_user->ID,
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => '_starred_by',
				'value' => 'none',
				'compare' => '!=',
			)
		)
	);
	$starred_tickets = get_posts($post_args);
	$starred = null;
	if(!empty($starred_tickets)){
		foreach ($starred_tickets as $key => $ticket) {
			$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
			if(in_array($current_user->ID, $stars)){
				$starred[] = 1;
			}
		}
	}
}



if($user_type == 'agent'){

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			)
		),
	);
	$open_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'closed',
		'meta_query' => array(
			array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			),
			array(
				'key' => '_ticket_status',
				'value' => 'closed',
				'compare' => '=',
			),
		),
	);
	$closed_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => array('publish', 'closed'),
		'meta_query' => array(
			array(
				'key' => '_post_visibility',
				'value' => 'public',
				'compare' => '=',
			),
			array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			)
		)
	);
	$public_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			),
			array(
				'key' => '_starred_by',
				'value' => 'none',
				'compare' => '!=',
			)
		),
	);
	$starred_tickets = get_posts($post_args);
	$starred = null;
	if(!empty($starred_tickets)){
		foreach ($starred_tickets as $key => $ticket) {
			$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
			if(in_array($current_user->ID, $stars)){
				$starred[] = 1;
			}
		}
	}
}



if($user_type == 'admin'){

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
		/*'meta_query' => array(
			array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			)
		),*/
	);
	$open_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => -1,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'closed',
		'meta_query' => array(
			array(
				'key' => '_ticket_status',
				'value' => 'closed',
				'compare' => '=',
			)
		),
	);
	$closed_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => array('publish', 'closed'),
		'meta_query' => array(
			array(
				'key' => '_post_visibility',
				'value' => 'public',
				'compare' => '=',
			),
			/*array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			)*/
		)
	);
	$public_tickets = get_posts($post_args);

	$post_args = null;
	$post_args = array(
		'orderby' => 'post_date',
		'posts_per_page' => 10000000000,
		'order' => 'ASC',
		'post_type' => 'cjsupport',
		'post_status' => 'publish',
		'meta_query' => array(
			/*array(
				'key' => '_assigned_to',
				'value' => $current_user->ID,
				'compare' => '=',
			),*/
			array(
				'key' => '_starred_by',
				'value' => 'none',
				'compare' => '!=',
			)
		),
	);
	$starred_tickets = get_posts($post_args);
	$starred = null;
	if(!empty($starred_tickets)){
		foreach ($starred_tickets as $key => $ticket) {
			$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
			if(in_array($current_user->ID, $stars)){
				$starred[] = 1;
			}
		}
	}
}


// Response required

$post_args = null;
$post_args = array(
	'orderby' => 'post_date',
	'posts_per_page' => 10000000000,
	'order' => 'ASC',
	'post_type' => 'cjsupport',
	'post_status' => 'publish',
	'meta_query' => array(
		array(
			'key' => '_awaiting_response_from',
			'value' => $current_user->ID,
			'compare' => '!=',
		)
	),
);
$awaiting_response_tickets = get_posts($post_args);
$awaiting_response = null;
if(!empty($awaiting_response_tickets)){
	$support_staff = cjsupport_get_option('support_staff');
	$user_type = cjsupport_user_type();
	foreach ($awaiting_response_tickets as $key => $ticket) {
		$awaiting_response_from = get_post_meta($ticket->ID, '_awaiting_response_from', true );
		$assigned_to = get_post_meta($ticket->ID, '_assigned_to', true );
		if($user_type == 'client' && in_array($awaiting_response_from, $support_staff) && $ticket->post_author == $current_user->ID){
			$awaiting_response[] = $ticket->ID;
		}
		if($user_type != 'client' && !in_array($awaiting_response_from, $support_staff) && $assigned_to == $current_user->ID){
			$awaiting_response[] = $ticket->ID;
		}
	}
}


$return['is_user_logged_in'] = is_user_logged_in();
$return['number_open_tickets'] = count($open_tickets);
$return['number_closed_tickets'] = count($closed_tickets);
$return['number_public_tickets'] = count($public_tickets);
$return['number_starred_tickets'] = count($starred);
$return['number_response_required'] = count($awaiting_response);


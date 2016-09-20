<?php
global $wpdb, $current_user;

$errors = null;
$page_title = sprintf(__('Search Results for <strong>"%s"</strong>', 'cjsupport'), $_POST['keywords']);
$keywords = $_POST['keywords'];
$user_id = $_POST['user_id'];
$user_type = cjsupport_user_type($current_user->ID);
$post_author = ($user_type == 'client') ? $current_user->ID : null;
$check_id = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_uid' AND meta_value = '{$keywords}'");
$meta_query = null;
if(!empty($check_id)){
	$meta_query[] = array(
	   'key' => '_uid',
	   'value' => $keywords,
	   'compare' => 'LIKE'
	);

	$tickets = get_posts(
		array(
			'posts_per_page' => 100,
			'post_type' => 'cjsupport',
			'post_status' => 'any',
			'meta_query' => $meta_query,
		)
	);

}else{

	$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'cjsupport' AND post_title LIKE '%{$keywords}%' OR post_content LIKE '%{$keywords}%'");
	if(!empty($posts)){
		foreach ($posts as $key => $post) {
			$post_info = cjsupport_post_info($post->ID);
			if(current_user_can('manage_options')){
				$post_ids[$post->ID] = $post_info['ID'];
			}
			if($post_info['post_author'] == $current_user->ID || @$post_info['_assigned_to'] == $current_user->ID){
				$post_ids[$post->ID] = $post_info['ID'];
			}
		}
	}

	$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_content LIKE '%{$keywords}%' AND comment_approved = '1' AND comment_type = 'comment'");
	if(!empty($comments)){
		foreach ($comments as $key => $comment) {
			$post_info = cjsupport_post_info($comment->comment_post_ID);
			if($post_info['post_type'] == 'cjsupport' && current_user_can('manage_options')){
				$post_ids[$comment->comment_post_ID] = $post_info['ID'];
			}
			if($post_info['post_type'] == 'cjsupport' && $post_info['post_author'] == $current_user->ID || $post_info['_assigned_to'] == $current_user->ID){
				$post_ids[$comment->comment_post_ID] = $post_info['ID'];
			}
		}
	}

	$users = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE display_name LIKE '%{$keywords}%' OR user_login LIKE '%{$keywords}%' OR user_email LIKE '%{$keywords}%'");

	foreach ($users as $key => $user) {
		$user_posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_author = '{$user->ID}' AND post_type = 'cjsupport'");
		$user_comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_author_email = '{$user->user_email}' AND comment_approved = '1' AND comment_type = 'comment'");

		if(!empty($user_posts)){
			foreach ($user_posts as $key => $post) {
				$post_info = cjsupport_post_info($post->ID);
				if(current_user_can('manage_options')){
					$post_ids[$post->ID] = $post_info['ID'];
				}
				if($post_info['post_author'] == $current_user->ID || @$post_info['_assigned_to'] == $current_user->ID){
					$post_ids[$post->ID] = $post_info['ID'];
				}
			}
		}

		if(!empty($user_comments)){
			foreach ($user_comments as $key => $comment) {
				$post_info = cjsupport_post_info($comment->comment_post_ID);
				if($post_info['post_type'] == 'cjsupport' && current_user_can('manage_options')){
					$post_ids[$comment->comment_post_ID] = $post_info['ID'];
				}
				if($post_info['post_type'] == 'cjsupport' && $post_info['post_author'] == $current_user->ID || $post_info['_assigned_to'] == $current_user->ID){
					$post_ids[$comment->comment_post_ID] = $post_info['ID'];
				}
			}
		}
	}


	$posts_in = (isset($post_ids) && is_array($post_ids)) ? array_keys($post_ids) : array(0);

	$tickets = get_posts(
		array(
			'posts_per_page' => 100,
			'post_type' => 'cjsupport',
			'post_status' => 'any',
			'include' => $posts_in,
		)
	);

}


if(!empty($tickets)){

	$count = -1;
	foreach ($tickets as $key => $ticket) {
		$stars = explode(',', get_post_meta($ticket->ID, '_starred_by', true));
		$starred = (in_array($current_user->ID, $stars)) ? 1 : 0;

		$count++;
		$return_tickets[$count]['ID'] = $ticket->ID;
		$return_tickets[$count]['subject'] = $ticket->post_title;
		$return_tickets[$count]['user_avatar'] = cjsupport_get_image_url(get_avatar($ticket->post_author, '125'));
		$return_tickets[$count]['ticket_meta'] = cjsupport_ticket_meta($ticket->ID);
		$return_tickets[$count]['ticket_status'] = $ticket->post_status;
		$return_tickets[$count]['starred'] = $starred;
		$return_tickets[$count]['order'] = strtotime($ticket->post_modified_gmt);

	}



	$return['status'] = 'success';
	$return['response'] = $return_tickets;
}

if(empty($tickets)){
	$return['status'] = 'errors';
	$return['response'] = __('No tickets found.', 'cjsupport');
}



$return['page_title'] = $page_title;
$return['meta_query'] = $meta_query;
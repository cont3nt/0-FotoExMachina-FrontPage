<?php
global $wpdb, $current_user;

$errors = null;

$tax_query[] = array(
	'taxonomy' => 'cjsupport_products',
	'field'    => 'slug',
	'terms'    => $_POST['product_slug'],
);

$term = get_term_by( 'slug', $_POST['product_slug'], 'cjsupport_products' );
$page_title = sprintf(__('FAQs ~ %s', 'cjsupport'), $term->name);

$args = array(
	'posts_per_page' => 100000,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'post_type' => 'cjsupport_faqs',
	'post_status' => 'publish',
	'tax_query' => $tax_query,
);
$faqs = get_posts($args);

if(!empty($faqs)){
	$faqs_array = array();
	$count = -1;
	foreach ($faqs as $key => $post) {
		$count++;
		$faqs_array[$count]['title'] = $post->post_title;
		$faqs_array[$count]['link'] = get_permalink($post->ID);
		$faqs_array[$count]['content'] = wpautop($post->post_content);
		$faqs_array[$count]['order'] = $count;
		# code...
	}
	$return['status'] = 'success';
	$return['page_title'] = $page_title;
	$return['response'] = $faqs_array;
}else{
	$return['status'] = 'errors';
	$return['page_title'] = $page_title;
	$return['response'] = __('No faqs found.', 'cjsupport');
}
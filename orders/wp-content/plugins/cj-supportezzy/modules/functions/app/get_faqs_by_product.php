<?php
global $wpdb, $current_user;

$errors = null;
$term_args = array(
	'hide_empty' 		=> true,
	'orderby'           => 'name',
    'order'             => 'ASC',
);
$products = get_terms('cjsupport_products', $term_args);
$products_array = array();
if(!is_wp_error($products) && !empty($products)){
	$count = -1;
	foreach ($products as $key => $product) {
		$count++;
		$products_array[$count]['term_id'] = $product->term_id;
		$products_array[$count]['product_slug'] = $product->slug;
		$products_array[$count]['product_title'] = $product->name;


		$post_args = array(
			'post_type' => 'cjsupport_faqs',
			'post_status' => 'publish',
			'tax_query' => array(
				array(
					'taxonomy' => 'cjsupport_products',
					'field'    => 'slug',
					'terms'    => $product->slug,
				),
			),
		);
		$product_posts = get_posts($post_args);
		if(!empty($product_posts)){
			foreach ($product_posts as $key => $value) {
				$products_array[$count]['posts'][$key] = array(
					'post_title' => $value->post_title,
					'post_link' => get_permalink($value->ID)
				);
			}
		}else{
			unset($products_array[$count]);
		}
	}
}

if(empty($products_array)){
	$return['status'] = 'errors';
	$return['response'] = __('No faqs found.', 'cjsupport');
}else{
	$return['status'] = 'success';
	$return['response'] = $products_array;
}


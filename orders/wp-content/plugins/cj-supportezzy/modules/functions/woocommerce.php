<?php

// Sync woocommerce products
function cjsupport_sync_woocommerce_products(){
	global $wpdb, $current_user;
	$args = array( 'post_type' => 'product', 'posts_per_page' => 1000000000 );
	$woocommerce_products = get_posts($args);
	$exclude_woocommerce_products = cjsupport_get_option('exclude_woocommerce_products');
	if(!empty($woocommerce_products)){
		foreach ($woocommerce_products as $key => $wproduct) {
			if(!in_array($wproduct->ID, $exclude_woocommerce_products)){
				$woocommerce_products_array[$wproduct->ID] = $wproduct->post_title;
				$term_name = 'wc-'.$wproduct->ID;
				if(!term_exists( $term_name, 'cjsupport_products')){
					$term_data = wp_insert_term(
					  	$wproduct->post_title, // the term
					  	'cjsupport_products', // the taxonomy
					  	array(
					    	'description'=> '',
					    	'slug' => $term_name,
					    	'parent'=> ''
					  	)
					);
				}
				if(term_exists( $term_name, 'cjsupport_products')){
					$term_to_update = get_term_by( 'slug', $term_name, 'cjsupport_products');
					wp_update_term($term_to_update->term_id, 'cjsupport_products', array(
					  'name' => $wproduct->post_title
					));

				}
			}
		}
	}
}

$sync_woocommerce = cjsupport_get_option('import_woocommerce_products');
if($sync_woocommerce == 'yes' && cjsupport_is_woocommerce_active()){
	add_action('admin_init', 'cjsupport_sync_woocommerce_products');
}


// Check if woocommerce is active
function cjsupport_is_woocommerce_active(){
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	    return true;
	}else{
		return false;
	}
}
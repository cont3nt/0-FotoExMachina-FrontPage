<?php
function cjsupport_faqs( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array(
		'return' => null,
		'category' => 'all',
		'border_color' => '#ededed',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$faq_categories_array['all'] = __('All Products', 'cjsupport');
	$faqs_categories = get_terms( 'cjsupport_products' );
	foreach ($faqs_categories as $key => $value) {
		$faq_categories_array[$value->term_id] = $value->name;
	}

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will run the app within a page or post.', 'cjsupport'),
		'category' => array(__('Select FAQs Product', 'cjsupport'), 'dropdown', $faq_categories_array, __('You can choose all or specific product to display the faqs.', 'cjsupport')),
		'border_color' => array(__('Border Color', 'cjsupport'), 'color', '#ededed', __('Specify border color', 'cjsupport')),
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}
	$html = '';
	ob_start();
	if($category == 'all'){
		include('html/faqs.php');
	}else{
		include('html/faqs-category.php');
	}
	$html = ob_get_contents();
	ob_end_clean();
	$display[] = do_shortcode($html);
	if($return == null){
	    return implode(null, $display);
	}else{
	    return serialize($options);
	}
}
add_shortcode( 'cjsupport_faqs', 'cjsupport_faqs' );


function cjsupport_faqs_scripts(){
	wp_enqueue_style('cjsupport-faqs', cjsupport_item_path('item_url') . '/assets/css/faqs.css', null, cjsupport_item_info('item_version'), 'screen');
	wp_enqueue_script('cjsupport-faqs-js', cjsupport_item_path('item_url') . '/assets/js/cjsupport-faqs.js', array('jquery'), cjsupport_item_info('item_version'), true );
}

add_action( 'wp_enqueue_scripts' , 'cjsupport_faqs_scripts');
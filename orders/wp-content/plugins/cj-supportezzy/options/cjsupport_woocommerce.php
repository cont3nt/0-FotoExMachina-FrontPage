<?php
global $cjsupport_item_options;

$woocommerce = cjsupport_item_path('item_dir').'/modules/functions/woocommerce.php';
require_once($woocommerce);

if(cjsupport_is_woocommerce_active()){
	$args = array( 'post_type' => 'product', 'posts_per_page' => 1000000000 );
	$woocommerce_products = get_posts($args);
	if(!empty($woocommerce_products)){
		$woocommerce_products_array['none'] = __('None', 'cjsupport');
		foreach ($woocommerce_products as $key => $wproduct) {
			$woocommerce_products_array[$wproduct->ID] = $wproduct->post_title;
		}
	}else{
		$woocommerce_products_array['none'] = __('No products found in Woocommerce.', 'cjsupport');
	}
}else{
	$woocommerce_products_array['none'] = __('Woocommerce is not active on this website.', 'cjsupport');
}


$yes_no_array = array(
	'yes' => __('Yes', 'cjsupport'),
	'no' => __('No', 'cjsupport'),
);

$cjsupport_item_options['cjsupport_woocommerce'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_woocommerce_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Woocommerce Integration', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'import_woocommerce_products',
		'label' => __('Import Woocommerce Products', 'cjsupport'),
		'info' => __('If you choose yes, all your woocommerce products will be synced with support products', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'multiselect',
		'id' => 'exclude_woocommerce_products',
		'label' => __('Exclude products', 'cjsupport'),
		'info' => __('You can select Woocommerce products, you do not want to be synced with support app.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => array('none'),
		'options' => $woocommerce_products_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => '',
		'label' => __('Save Settings', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

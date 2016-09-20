<?php
global $cjsupport_item_options;

$social_services_array = array(
	'Facebook' => __('Facebook', 'cjsupport'),
	'Twitter' => __('Twitter', 'cjsupport'),
);

$cjsupport_item_options['plugin_addon_options'] = array(
	array(
		'type' => 'textarea',
		'id' => 'cjsupport_email_routes',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);
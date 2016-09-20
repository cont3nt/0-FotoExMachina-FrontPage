<?php

$cjsupport_item_options['cjsupport_custom_css'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_custom_css',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom CSS Code', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
			'type' => 'code-css',
			'id' => 'custom_css',
			'label' => __('Custom CSS Code', 'cjsupport'),
			'info' => __('<p>Write your custom css code here.</p>', 'cjsupport'),
			'suffix' => '',
			'prefix' => '',
			'default' => '<style type="text/css">
	/* add custom css code */
</style>',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
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
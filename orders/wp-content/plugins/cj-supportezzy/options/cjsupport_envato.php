<?php
global $cjsupport_item_options;

$enable_disable_array = array(
	'enable' => __('Enable', 'cjsupport'),
	'disable' => __('Disable', 'cjsupport'),
);

$yes_no_array = array(
	'yes' => __('Yes', 'cjsupport'),
	'no' => __('No', 'cjsupport'),
);

$manual_sync_button = __('<span class="red">Please specify your Envato Username and API Key.</span>', 'cjsupport');

if(cjsupport_get_option('envato_username') != '' && cjsupport_get_option('envato_apikey') != ''){
	$manual_sync_button = '<a class="btn btn-success" href="'.cjsupport_string(cjsupport_callback_url('cjsupport_envato')).'cjsupport_action=import-envato-products">'.__('Import Envato Products', 'cjsupport').'</a>';
}

$cjsupport_item_options['cjsupport_envato'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_envato_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Envato Integration', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'import_envato_products',
		'label' => __('Import Envato Products', 'cjsupport'),
		'info' => __('If you choose yes, all your envato products will be synced with support products', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'envato_username',
		'label' => __('Envato Username <span class="red">(required)</span>', 'cjsupport'),
		'info' => __('Specify your envato username', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'envato_apikey',
		'label' => __('Envato API Key <span class="red">(required)</span>', 'cjsupport'),
		'info' => __('<p>Specify your envato API Key.</p>You can find your Envato API Key by visiting your Account page then clicking the My Settings tab, <br>At the bottom of the page you will find your account API keys and a button to regenerate new as needed.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
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
	array(
		'type' => 'sub-heading',
		'id' => 'cjsupport_envato_manual_import',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Manual Sync', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'gap',
		'id' => 'cjsupport_envato_manual_import_gap',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'cjsupport_envato_manual_import_button',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $manual_sync_button,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

<?php
global $cjsupport_item_options;

$login_message = __('<h2>Login or Register</h2>', 'cjsupport');
$login_message .= __('<p>You must %%login_link%% or %%register_link%% to submit a support ticket.</p>', 'cjsupport');

$enable_disable_array = array(
	'enable' => __('Enable', 'cjsupport'),
	'disable' => __('Disable', 'cjsupport'),
);

$yes_no_array = array(
	'yes' => __('Yes', 'cjsupport'),
	'no' => __('No', 'cjsupport'),
);

$public_private_array = array(
	'public' => __('Public', 'cjsupport'),
	'private' => __('Private', 'cjsupport'),
);

$layout_opitons = array(
	'default' => cjsupport_item_path('item_url').'/options/images/default-layout.png',
	'no-header' => cjsupport_item_path('item_url').'/options/images/sidebar-layout.png',
	'no-logo' => cjsupport_item_path('item_url').'/options/images/blank-layout.png',
);

$communication_array = array(
	'web' => __('<strong>Web Replies</strong>: Enable new ticket creation and replies via web only.', 'cjsupport'),
	'imap' => __('<strong>Email Piping</strong>: Enable new ticket creation and replies via both web and emails.', 'cjsupport'),
);


$cjsupport_item_options['cjsupport_app_styles'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_styles_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('App Styles & Colors', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'header_bg_color',
		'label' => __('Header Background Color', 'cjsupport'),
		'info' => __('Choose background color for the header', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '#DB493C',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'header_text_color',
		'label' => __('Header Text Color', 'cjsupport'),
		'info' => __('Choose text color for the header', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '#FFFFFF',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'logo_color',
		'label' => __('Logo Color', 'cjsupport'),
		'info' => __('Choose color for the logo in the header bar.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '#FFFFFF',
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